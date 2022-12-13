<?php

namespace Cundd\Fleet\Info;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;

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
        $applicationContext = (string)Environment::getContext();
        $version = new Typo3Version();

        return [
            'name'        => 'TYPO3',
            'version'     => $version->getVersion(),
            'installMode' => $this->detectInstallMode(),
            'meta'        => [
                'branch'             => $version->getBranch(),
                'applicationContext' => $applicationContext,
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

    /**
     * @return string
     */
    private function detectInstallMode(): string
    {
        if (Environment::isComposerMode()) {
            return 'composer';
        }

        return is_link(Environment::getPublicPath() . '/typo3') ? 'symlink' : 'file';
    }
}
