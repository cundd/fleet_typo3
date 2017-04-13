<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 13/04/2017
 * Time: 11:20
 */

namespace Cundd\Fleet\Tests\Functional\Info;

use Cundd\Fleet\Info\ExtensionService;
use Cundd\Fleet\Tests\Functional\AbstractCase;
use TYPO3\CMS\Core\Package\PackageManager;

class ExtensionServiceTest extends AbstractCase
{
    /**
     * @var ExtensionService
     */
    private $fixture;

    protected function setUp()
    {
        parent::setUp();
        /** @var PackageManager $packageManager */
        $packageManager = $this->objectManager->get(PackageManager::class);
        $this->fixture = new ExtensionService($packageManager);
    }

    /**
     * @test
     */
    public function getAllPackagesTest()
    {
        $packages = $this->fixture->getAllPackages();

        $this->assertActive($packages, 'core');
        $this->assertActive($packages, 'extbase');
        $this->assertActive($packages, 'install');

        $this->assertInactive($packages, 'recycler');
        $this->assertInactive($packages, 'workspaces');
    }

    /**
     * @test
     */
    public function getActivePackagesTest()
    {
        $packages = $this->fixture->getActivePackages();

        $this->assertActive($packages, 'core');
        $this->assertActive($packages, 'extbase');
        $this->assertActive($packages, 'install');

        $this->assertArrayNotHasKey('recycler', $packages);
    }

    /**
     * @test
     */
    public function getInactivePackagesTest()
    {
        $packages = $this->fixture->getInactivePackages();

        $this->assertInactive($packages, 'recycler');
        $this->assertInactive($packages, 'workspaces');

        $this->assertArrayNotHasKey('extbase', $packages);
    }

    /**
     * @param array  $packages
     * @param string $key
     */
    private function assertActive(array $packages, $key)
    {
        $this->assertArrayHasKey($key, $packages, "Package '$key' not found in packages array");
        $this->assertSame('active', $packages[$key]['state'], "Package '$key' is not active");
        $this->assertTrue(version_compare($packages[$key]['version'], TYPO3_branch) >= 0);
    }

    /**
     * @param array  $packages
     * @param string $key
     */
    private function assertInactive(array $packages, $key)
    {
        $this->assertArrayHasKey($key, $packages, "Package '$key' not found in packages array");
        $this->assertSame('inactive', $packages[$key]['state'], "Package '$key' is not inactive");
        $this->assertTrue(version_compare($packages[$key]['version'], TYPO3_branch) >= 0);
    }
}
