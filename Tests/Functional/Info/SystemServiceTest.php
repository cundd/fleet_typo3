<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 13/04/2017
 * Time: 11:20
 */

namespace Cundd\Fleet\Tests\Functional\Info;

use Cundd\Fleet\Info\SystemService;
use Cundd\Fleet\Tests\Functional\AbstractCase;

class SystemServiceTest extends AbstractCase
{
    /**
     * @var SystemService
     */
    private $fixture;

    protected function setUp()
    {
        parent::setUp();
        $this->fixture = new SystemService();
    }

    /**
     * @test
     */
    public function getInformationTest()
    {
        $information = $this->fixture->getInformation();
        $this->assertTYPO3Information($information['typo3']);
        $this->assertPlatformInformation($information['platform']);
    }

    /**
     * @test
     */
    public function getTYPO3InformationTest()
    {
        $information = $this->fixture->getTYPO3Information();
        $this->assertTYPO3Information($information);
    }

    /**
     * @test
     */
    public function getPlatformInformationTest()
    {
        $information = $this->fixture->getPlatformInformation();
        $this->assertPlatformInformation($information);
    }

    /**
     * @param $information
     */
    private function assertTYPO3Information(array $information)
    {
        $this->assertArrayHasKey('applicationContext', $information);
        $this->assertInternalType('string', $information['applicationContext']);
        $this->assertSame(TYPO3_version, $information['version']);
        $this->assertSame(TYPO3_branch, $information['branch']);
    }

    /**
     * @param $information
     */
    private function assertPlatformInformation(array $information)
    {
        $this->assertInternalType('string', $information['host']);
        $this->assertSame(PHP_VERSION, $information['php']);
        $this->assertSame(PHP_SAPI, $information['phpSapi']);
        $this->assertInternalType('array', $information['os']);
        $this->assertInternalType('string', $information['os']['vendor']);
        $this->assertInternalType('string', $information['os']['version']);
        $this->assertInternalType('string', $information['os']['machine']);
        $this->assertInternalType('string', $information['os']['info']);
    }
}
