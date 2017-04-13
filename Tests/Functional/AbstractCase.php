<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 24.12.14
 * Time: 12:45
 */

namespace Cundd\Fleet\Tests\Functional;

//use Cundd\Fleet\Http\RestRequestInterface;
//use Cundd\Fleet\Tests\ClassBuilderTrait;
//use Cundd\Fleet\Tests\RequestBuilderTrait;
//use Cundd\Fleet\Tests\ResponseBuilderTrait;
use TYPO3\CMS\Core\Tests\FunctionalTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;

abstract class AbstractCase extends FunctionalTestCase
{
//    use ResponseBuilderTrait;
//    use RequestBuilderTrait;
//    use ClassBuilderTrait;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    protected function setUp()
    {
        parent::setUp();

//        $_SERVER['HTTP_HOST'] = 'rest.cundd.net';

        $this->objectManager = new ObjectManager();
    }

//    /**
//     * Build a new request with the given URI
//     *
//     * @param string $uri
//     * @param string $format
//     * @return RestRequestInterface
//     */
//    public function buildRequestWithUri($uri, $format = null)
//    {
//        return \Cundd\Fleet\Tests\RequestBuilderTrait::buildTestRequest(
//            $uri,
//            null,       // $method
//            array(),    // $params
//            array(),    // $headers
//            null,       // $rawBody
//            null,       // $parsedBody
//            $format
//        );
//    }

    /**
     * Imports a data set represented as XML into the test database,
     *
     * @param string $path Absolute path to the XML file containing the data set to load
     * @return void
     * @throws \Exception
     */
    protected function importDataSet($path)
    {
        if (method_exists('\TYPO3\CMS\Core\Tests\FunctionalTestCase', 'importDataSet')) {
            parent::importDataSet($path);

            return;
        }

        if (!is_file($path)) {
            throw new \Exception(
                'Fixture file ' . $path . ' not found',
                1376746261
            );
        }

        $database = $this->getDatabaseConnection();

        $xml = simplexml_load_file($path);
        $foreignKeys = array();

        /** @var $table \SimpleXMLElement */
        foreach ($xml->children() as $table) {
            $insertArray = array();

            /** @var $column \SimpleXMLElement */
            foreach ($table->children() as $column) {
                $columnName = $column->getName();
                $columnValue = null;

                if (isset($column['ref'])) {
                    list($tableName, $elementId) = explode('#', $column['ref']);
                    $columnValue = $foreignKeys[$tableName][$elementId];
                } elseif (isset($column['is-NULL']) && ($column['is-NULL'] === 'yes')) {
                    $columnValue = null;
                } else {
                    $columnValue = (string)$table->$columnName;
                }

                $insertArray[$columnName] = $columnValue;
            }

            $tableName = $table->getName();
            $result = $database->exec_INSERTquery($tableName, $insertArray);
            if ($result === false) {
                $this->markTestSkipped(
                    sprintf(
                        'Error when processing fixture file: %s. Can not insert data to table %s: %s',
                        $path,
                        $tableName,
                        $database->sql_error()
                    )
                );
            }
            if (isset($table['id'])) {
                $elementId = (string)$table['id'];
                $foreignKeys[$tableName][$elementId] = $database->sql_insert_id();
            }
        }
    }

    /**
     * Get DatabaseConnection instance - $GLOBALS['TYPO3_DB']
     *
     * This method should be used instead of direct access to
     * $GLOBALS['TYPO3_DB'] for easy IDE auto completion.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * @param mixed  $propertyValue
     * @param string $propertyKey
     * @param object $object
     * @return object
     */
    public function injectPropertyIntoObject($propertyValue, $propertyKey, $object)
    {
        $reflectionMethod = new \ReflectionProperty(get_class($object), $propertyKey);
        $reflectionMethod->setAccessible(true);
        $reflectionMethod->setValue($object, $propertyValue);

        return $object;
    }
}
