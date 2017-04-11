<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 11/04/2017
 * Time: 21:20
 */

namespace Cundd\Fleet\Command;

use Cundd\Fleet\Info\ExtensionService;
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
        $this->output(
            json_encode(
                [
                    'packages' => [
                        'active'   => $this->extensionService->getActivePackages(),
                        'inactive' => $this->extensionService->getInactivePackages(),
                        'all'      => $this->extensionService->getAllPackages(),
                    ],
                ],
                JSON_PRETTY_PRINT
            )
        );

        $this->sendAndExit();
    }
}
