<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;

/**
 * @phpstan-type OSInformation array{vendor:string, version:string, machine:string, info:string}
 * @phpstan-type Typo3Information array{name:string, version:string, installMode:string, meta:array{branch:string, applicationContext:string}}
 * @phpstan-type Platform array{language:string, version:string, sapi:string, host:string, os:OSInformation}
 * @phpstan-type SystemInformation array{application:Typo3Information, platform:Platform}
 */
class SystemService implements ServiceInterface
{
    /**
     * @return SystemInformation
     */
    public function getInformation(): array
    {
        return [
            'platform'    => $this->getPlatformInformation(),
            'application' => $this->getTYPO3Information(),
        ];
    }

    /**
     * @return Typo3Information
     */
    public function getTYPO3Information(): array
    {
        $applicationContext = (string) Environment::getContext();
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
     * @return Platform
     */
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
