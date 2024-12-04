<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

/**
 * @phpstan-import-type FleetInformation from FleetService
 * @phpstan-import-type SystemInformation from SystemService
 * @phpstan-import-type PackagesInformation from ExtensionService
 */
class AllInformationService implements ServiceInterface
{
    public function __construct(
        private readonly ExtensionService $extensionService,
        private readonly SystemService $systemService,
        private readonly FleetService $fleetService,
    ) {
    }

    /**
     * @return array{fleet: FleetInformation, system: SystemInformation, packages:PackagesInformation}
     */
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
