<?php

namespace Cundd\Fleet\Command;

use Cundd\Fleet\Info\AllInformationService;
use Cundd\Fleet\Info\ExtensionService;
use Cundd\Fleet\Info\FleetService;
use Cundd\Fleet\Info\SystemService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

class InfoCommandController extends CommandController
{
    /**
     * @var AllInformationService
     */
    private $allInformationService;

    /**
     * @param ExtensionService $extensionService
     * @param SystemService    $systemService
     * @param FleetService     $fleetService
     */
    public function __construct(
        ExtensionService $extensionService,
        SystemService $systemService,
        FleetService $fleetService
    ) {
        $this->allInformationService = new AllInformationService(
            $extensionService,
            $systemService,
            $fleetService
        );
    }

    /**
     * Print information
     *
     * @param string $key Type of information to fetch
     * @return void
     */
    public function infoCommand($key = '')
    {
        $allInformation = $this->allInformationService->getInformation();

        if ($key) {
            $information = ArrayUtility::getValueByPath($allInformation, $key, '.');
        } else {
            $information = $allInformation;
        }

        $this->output(json_encode($information, JSON_PRETTY_PRINT));

        $this->sendAndExit();
    }
}
