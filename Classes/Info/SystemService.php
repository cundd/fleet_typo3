<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;

class SystemService implements ServiceInterface
{
    public function getInformation(): array
    {
        return [
            'platform'    => $this->getPlatformInformation(),
            'application' => $this->getTYPO3Information(),
        ];
    }

    public function getTYPO3Information(): array
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

    public function getPlatformInformation(): array
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

    private function detectInstallMode(): string
    {
        if (Environment::isComposerMode()) {
            return 'composer';
        }

        return is_link(Environment::getPublicPath() . '/typo3') ? 'symlink' : 'file';
    }
}
