<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 11/04/2017
 * Time: 21:27
 */

namespace Cundd\Fleet\Info;

use Closure;
use TYPO3\CMS\Core\Package\Package;


/**
 * Service to fetch extension information
 */
class ExtensionService
{
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
    public function getInformation()
    {
        /** @var \TYPO3\CMS\Core\Package\Package $package */

        var_dump(array_keys($this->packageManager->getActivePackages()));
        var_dump(array_keys($this->packageManager->getAvailablePackages()));

        var_dump(
            array_diff(
                array_keys($this->packageManager->getAvailablePackages()),
                array_keys($this->packageManager->getActivePackages())
            )
        );


        var_dump(
            json_encode(
                (array)$this->packageManager->getActivePackages()['rest']->getPackageMetaData(),
                JSON_PRETTY_PRINT
            )
        );
        var_dump(
            json_encode(
                (array)$this->packageManager->getActivePackages()['frontend']->getPackageMetaData(),
                JSON_PRETTY_PRINT
            )
        );
        var_dump(
            json_encode(
                (array)$this->packageManager->getActivePackages()['info']->getPackageMetaData(),
                JSON_PRETTY_PRINT
            )
        );

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
        return array_map([$this, 'getPackageData'], $this->packageManager->getActivePackages());
    }

    /**
     * @return array
     */
    public function getInactivePackages()
    {
        $allPackages = $this->packageManager->getAvailablePackages();
        $activePackages = $this->packageManager->getActivePackages();
        $inactivePackageKeys = array_diff_key($allPackages, $activePackages);

        return array_map([$this, 'getPackageData'], $inactivePackageKeys);
    }

    /**
     * @param Package $package
     * @return array
     */
    private function getPackageData(Package $package)
    {
        $meta = $package->getPackageMetaData();

        return [
            'key'         => $meta->getPackageKey(),
            'version'     => $meta->getVersion(),
            'description' => $meta->getDescription(),
        ];
    }
}
