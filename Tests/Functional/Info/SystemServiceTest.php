<?php

declare(strict_types=1);

namespace Cundd\Fleet\Tests\Functional\Info;

use Cundd\Fleet\Info\SystemService;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @phpstan-import-type Typo3Information from SystemService
 * @phpstan-import-type Platform from SystemService
 */
class SystemServiceTest extends FunctionalTestCase
{
    private SystemService $fixture;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixture = new SystemService();
    }

    protected function tearDown(): void
    {
        unset($this->fixture);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getInformationTest(): void
    {
        $information = $this->fixture->getInformation();
        $this->assertTYPO3Information($information['application']);
        $this->assertPlatformInformation($information['platform']);
    }

    /**
     * @test
     */
    public function getTYPO3InformationTest(): void
    {
        $information = $this->fixture->getTYPO3Information();
        $this->assertTYPO3Information($information);
    }

    /**
     * @test
     */
    public function getPlatformInformationTest(): void
    {
        $information = $this->fixture->getPlatformInformation();
        $this->assertPlatformInformation($information);
    }

    /**
     * @param Typo3Information $information
     */
    private function assertTYPO3Information(array $information): void
    {
        $this->assertSame('TYPO3', $information['name']);
        $this->assertSame((new Typo3Version())->getVersion(), $information['version']);

        $this->assertArrayHasKey('meta', $information);
        $this->assertIsArray($information['meta']);
        $this->assertArrayHasKey('applicationContext', $information['meta']);
        $this->assertIsString($information['meta']['applicationContext']);
        $this->assertSame((new Typo3Version())->getBranch(), $information['meta']['branch']);
    }

    /**
     * @param Platform $information
     */
    private function assertPlatformInformation(array $information): void
    {
        $this->assertIsString($information['host']);
        $this->assertSame('php', $information['language']);
        $this->assertSame(PHP_VERSION, $information['version']);
        $this->assertSame(PHP_SAPI, $information['sapi']);
        $this->assertIsArray($information['os']);
        $this->assertIsString($information['os']['vendor']);
        $this->assertIsString($information['os']['version']);
        $this->assertIsString($information['os']['machine']);
        $this->assertIsString($information['os']['info']);
    }
}
