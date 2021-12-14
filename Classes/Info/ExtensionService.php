<?php

namespace Cundd\Fleet\Info;

use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageInterface;
use TYPO3\CMS\Core\Package\PackageManager;

/**
 * Service to fetch extension information
 */
class ExtensionService implements ServiceInterface
{
    const STATE_ACTIVE = 'active';
    const STATE_INACTIVE = 'inactive';

    /**
     * @var PackageManager
     */
    private $packageManager;

    /**
     * Extension Service constructor
     *
     * @param PackageManager $packageManager
     */
    public function __construct(PackageManager $packageManager)
    {
        $this->packageManager = $packageManager;
    }

    /**
     * @inheritdoc
     */
    public function getInformation()
    {
        return [
            'active'   => $this->getActivePackages(),
            'inactive' => $this->getInactivePackages(),
            'all'      => $this->getAllPackages(),
        ];
    }

    /**
     * @return array[]
     */
    public function getAllPackages()
    {
        return array_map([$this, 'getPackageData'], $this->loadAllAvailablePackages());
    }

    /**
     * @return array[]
     */
    public function getActivePackages()
    {
        return array_map([$this, 'getPackageDataStateActive'], $this->loadActivePackages());
    }

    /**
     * @return array[]
     */
    public function getInactivePackages()
    {
        $inactivePackages = array_diff_key($this->loadAllAvailablePackages(), $this->loadActivePackages());

        return array_map([$this, 'getPackageDataStateInactive'], $inactivePackages);
    }

    /**
     * @param Package $package
     * @param string  $state Either self::STATE_ACTIVE, self::STATE_INACTIVE, or an empty string
     * @return array
     */
    private function getPackageData(Package $package, $state = '')
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
     * @param Package $package
     * @return array
     */
    private function getPackageDataStateActive(Package $package)
    {
        return $this->getPackageData($package, self::STATE_ACTIVE);
    }

    /**
     * @param Package $package
     * @return array
     */
    private function getPackageDataStateInactive(Package $package)
    {
        return $this->getPackageData($package, self::STATE_INACTIVE);
    }

    /**
     * @return PackageInterface[]
     */
    private function loadAllAvailablePackages()
    {
        return $this->packageManager->getAvailablePackages();
    }

    /**
     * @return PackageInterface[]
     */
    private function loadActivePackages()
    {
        return $this->packageManager->getActivePackages();
    }
}
