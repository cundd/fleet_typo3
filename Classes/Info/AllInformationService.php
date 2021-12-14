<?php

namespace Cundd\Fleet\Info;

class AllInformationService implements ServiceInterface
{
    /**
     * @var ExtensionService
     */
    private $extensionService;

    /**
     * @var SystemService
     */
    private $systemService;

    /**
     * @var FleetService
     */
    private $fleetService;

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
        $this->extensionService = $extensionService;
        $this->systemService = $systemService;
        $this->fleetService = $fleetService;
    }

    public function getInformation()
    {
        return [
            'fleet'    => $this->fleetService->getInformation(),
            'system'   => $this->systemService->getInformation(),
            'packages' => [
                'active'   => $this->extensionService->getActivePackages(),
                'inactive' => $this->extensionService->getInactivePackages(),
                'all'      => $this->extensionService->getAllPackages(),
            ],
        ];
    }
}
