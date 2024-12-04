<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageInterface;
use TYPO3\CMS\Core\Package\PackageManager;

/**
 * Service to fetch extension information
 *
 * @phpstan-type PackageInformation array{key:string, version:string, description:string, state:'active'|'inactive'}
 * @phpstan-type PackagesInformation array{active:array<string,PackageInformation>, inactive:array<string,PackageInformation>, all:array<string,PackageInformation>}
 */
class ExtensionService implements ServiceInterface
{
    private const STATE_ACTIVE = 'active';
    private const STATE_INACTIVE = 'inactive';

    public function __construct(private readonly PackageManager $packageManager)
    {
    }

    /**
     * @return PackagesInformation
     */
    public function getInformation(): array
    {
        return [
            'active'   => $this->getActivePackages(),
            'inactive' => $this->getInactivePackages(),
            'all'      => $this->getAllPackages(),
        ];
    }

    /**
     * @return array<string,PackageInformation>
     */
    public function getAllPackages(): array
    {
        return array_map([$this, 'getPackageData'], $this->loadAllAvailablePackages());
    }

    /**
     * @return array<string,PackageInformation>
     */
    public function getActivePackages(): array
    {
        return array_map([$this, 'getPackageDataStateActive'], $this->loadActivePackages());
    }

    /**
     * @return array<string,PackageInformation>
     */
    public function getInactivePackages(): array
    {
        $inactivePackages = array_diff_key($this->loadAllAvailablePackages(), $this->loadActivePackages());

        return array_map([$this, 'getPackageDataStateInactive'], $inactivePackages);
    }

    /**
     * @param string $state Either self::STATE_ACTIVE, self::STATE_INACTIVE, or an empty string
     *
     * @return PackageInformation
     */
    private function getPackageData(Package $package, string $state = ''): array
    {
        $meta = $package->getPackageMetaData();
        $packageKey = $meta->getPackageKey();

        if (!$state) {
            $state = $this->packageManager->isPackageActive($packageKey) ? self::STATE_ACTIVE : self::STATE_INACTIVE;
        }

        return [
            'key'         => $packageKey,
            'version'     => $meta->getVersion(),
            'description' => $meta->getDescription(),
            'state'       => $state,
        ];
    }

    /**
     * @return PackageInformation
     */
    private function getPackageDataStateActive(Package $package): array
    {
        return $this->getPackageData($package, self::STATE_ACTIVE);
    }

    /**
     * @return PackageInformation
     */
    private function getPackageDataStateInactive(Package $package): array
    {
        return $this->getPackageData($package, self::STATE_INACTIVE);
    }

    /**
     * @return PackageInterface[]
     */
    private function loadAllAvailablePackages(): array
    {
        return $this->packageManager->getAvailablePackages();
    }

    /**
     * @return PackageInterface[]
     */
    private function loadActivePackages(): array
    {
        return $this->packageManager->getActivePackages();
    }
}
