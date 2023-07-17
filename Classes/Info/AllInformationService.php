<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

class AllInformationService implements ServiceInterface
{
    /**
     * @param ExtensionService $extensionService
     * @param SystemService    $systemService
     * @param FleetService     $fleetService
     */
    public function __construct(
        private readonly ExtensionService $extensionService,
        private readonly SystemService $systemService,
        private readonly FleetService $fleetService
    ) {
    }

    public function getInformation(): array
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
