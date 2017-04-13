<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 11/04/2017
 * Time: 21:20
 */

namespace Cundd\Fleet\Command;

use Cundd\Fleet\Info\ExtensionService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

class InfoCommandController extends CommandController
{
    /**
     * @var ExtensionService
     */
    private $extensionService;

    /**
     * @param ExtensionService $extensionService
     */
    public function __construct(ExtensionService $extensionService)
    {
        $this->extensionService = $extensionService;
    }

    /**
     * Print information
     *
     * @param string $key Type of information to fetch
     * @return void
     */
    public function infoCommand($key = '')
    {
        $allInformation = $this->getAllInformation();

        if ($key) {
            $information = ArrayUtility::getValueByPath($allInformation, $key, '.');
        } else {
            $information = $allInformation;
        }

        $this->output(json_encode($information, JSON_PRETTY_PRINT));

        $this->sendAndExit();
    }

    /**
     * @return array
     */
    private function getAllInformation()
    {
        return [
            'packages' => [
                'active'   => $this->extensionService->getActivePackages(),
                'inactive' => $this->extensionService->getInactivePackages(),
                'all'      => $this->extensionService->getAllPackages(),
            ],
        ];
    }

}
