<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 11/04/2017
 * Time: 21:27
 */

namespace Cundd\Fleet\Info;

use TYPO3\CMS\Core\Package\Package;


/**
 * Service to fetch extension information
 */
class ExtensionService
{
    const STATE_ACTIVE = 'active';
    const STATE_INACTIVE = 'inactive';

    /**
     * @var \TYPO3\CMS\Core\Package\PackageManager
     */
    private $packageManager = null;

    /**
     * ExtensionService constructor.
     *
     * @param \TYPO3\CMS\Core\Package\PackageManager $packageManager
     */
    public function __construct(\TYPO3\CMS\Core\Package\PackageManager $packageManager)
    {
        $this->packageManager = $packageManager;
    }

    /**
     * @return array
     */
    public function getAllPackages()
    {
        return array_map([$this, 'getPackageData'], $this->packageManager->getAvailablePackages());
    }

    /**
     * @return array
     */
    public function getActivePackages()
    {
        return array_map([$this, 'getPackageDataStateActive'], $this->packageManager->getActivePackages());
    }

    /**
     * @return array
     */
    public function getInactivePackages()
    {
        $allPackages = $this->packageManager->getAvailablePackages();
        $activePackages = $this->packageManager->getActivePackages();
        $inactivePackageKeys = array_diff_key($allPackages, $activePackages);

        return array_map([$this, 'getPackageDataStateInactive'], $inactivePackageKeys);
    }

    /**
     *
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
}
