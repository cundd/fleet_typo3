<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 13/04/2017
 * Time: 11:20
 */

namespace Cundd\Fleet\Tests\Functional\Info;

use Cundd\Fleet\Constants;
use Cundd\Fleet\Info\FleetService;
use Cundd\Fleet\Info\SystemService;
use Cundd\Fleet\Tests\Functional\AbstractCase;

class FleetServiceTest extends AbstractCase
{
    /**
     * @var SystemService
     */
    private $fixture;

    protected function setUp()
    {
        parent::setUp();
        $this->fixture = new FleetService();
    }

    /**
     * @test
     */
    public function getInformationTest()
    {
        $information = $this->fixture->getInformation();
        $this->assertSame(Constants::PROTOCOL_VERSION, $information['protocol']);
        $this->assertSame(Constants::EXTENSION_VERSION, $information['extensionVersion']);
    }
}
