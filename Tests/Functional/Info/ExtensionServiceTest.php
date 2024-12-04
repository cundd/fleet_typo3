<?php

declare(strict_types=1);

namespace Cundd\Fleet\Tests\Functional\Info;

use Cundd\Fleet\Info\ExtensionService;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @phpstan-type PackageArray array{key:string,version:string,description:string,state:'active'|'inactive'}
 */
class ExtensionServiceTest extends FunctionalTestCase
{
    private ExtensionService $fixture;

    public function setUp(): void
    {
        parent::setUp();
        /** @var PackageManager $packageManager */
        $packageManager = GeneralUtility::makeInstance(PackageManager::class);
        $this->fixture = new ExtensionService($packageManager);
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

        $this->assertIsArray($information['active']);
        $this->assertIsArray($information['inactive']);
        $this->assertIsArray($information['all']);
    }

    /**
     * @test
     */
    public function getAllPackagesTest(): void
    {
        $packages = $this->fixture->getAllPackages();

        $this->assertActive($packages, 'core');
        $this->assertActive($packages, 'extbase');

        $this->assertInactiveAllowFailure($packages, 'recycler');
        $this->assertInactiveAllowFailure($packages, 'workspaces');
    }

    /**
     * @test
     */
    public function getActivePackagesTest(): void
    {
        $packages = $this->fixture->getActivePackages();

        $this->assertActive($packages, 'core');
        $this->assertActive($packages, 'extbase');

        $this->assertArrayNotHasKey('recycler', $packages);
    }

    /**
     * @test
     */
    public function getInactivePackagesTest(): void
    {
        $packages = $this->fixture->getInactivePackages();

        $this->assertIsArray($packages);
        $this->assertInactiveAllowFailure($packages, 'recycler');
        $this->assertInactiveAllowFailure($packages, 'workspaces');

        $this->assertArrayNotHasKey('extbase', $packages);
    }

    /**
     * @param array<string,PackageArray> $packages
     */
    private function assertActive(array $packages, string $key): void
    {
        $this->assertArrayHasKey($key, $packages, "Package '$key' not found in packages array");
        $this->assertSame('active', $packages[$key]['state'], "Package '$key' is not active");
        $this->assertTrue(version_compare($packages[$key]['version'], (new Typo3Version())->getBranch()) >= 0);
    }

    /**
     * @param array<string,PackageArray> $packages
     */
    private function assertInactive(array $packages, string $key): void
    {
        $this->assertArrayHasKey($key, $packages, "Package '$key' not found in packages array");
        $this->assertSame('inactive', $packages[$key]['state'], "Package '$key' is not inactive");
        $this->assertTrue(version_compare($packages[$key]['version'], (new Typo3Version())->getBranch()) >= 0);
    }

    /**
     * @param array<string,PackageArray> $packages
     * @param string $key
     */
    private function assertInactiveAllowFailure(array $packages, $key): void
    {
        if (isset($packages[$key])) {
            $this->assertInactive($packages, $key);
        }
    }
}
