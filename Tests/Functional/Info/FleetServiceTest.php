<?php

declare(strict_types=1);

namespace Cundd\Fleet\Tests\Functional\Info;

use Cundd\Fleet\Constants;
use Cundd\Fleet\Info\FleetService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FleetServiceTest extends FunctionalTestCase
{
    private FleetService $fixture;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixture = new FleetService();
    }

    protected function tearDown(): void
    {
        unset($this->fixture);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getInformationTest()
    {
        $information = $this->fixture->getInformation();
        $this->assertSame(Constants::PROTOCOL_VERSION, $information['protocol']);
        $this->assertSame(Constants::PROVIDER_VERSION, $information['providerVersion']);
        $this->assertSame(Constants::PROVIDER_NAME, $information['providerName']);
    }
}
