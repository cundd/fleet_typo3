<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 13/04/2017
 * Time: 13:40
 */

namespace Cundd\Fleet\Info;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class SystemService implements ServiceInterface
{
    /**
     * @return array
     */
    public function getInformation()
    {
        return [
            'platform'    => $this->getPlatformInformation(),
            'application' => $this->getTYPO3Information(),
        ];
    }

    /**
     * @return array
     */
    public function getTYPO3Information()
    {
        return [
            'name'    => 'TYPO3',
            'version' => TYPO3_version,
            'meta'    => [
                'branch'             => TYPO3_branch,
                'applicationContext' => (string)GeneralUtility::getApplicationContext(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getPlatformInformation()
    {
        return [
            'language' => 'php',
            'version'  => PHP_VERSION,
            'sapi'     => PHP_SAPI,
            'host'     => php_uname('n'),
            'os'       => [
                'vendor'  => php_uname('s'),
                'version' => php_uname('r'),
                'machine' => php_uname('m'),
                'info'    => php_uname('v'),
            ],
        ];
    }
}
