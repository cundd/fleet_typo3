<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 13/04/2017
 * Time: 20:42
 */

namespace Cundd\Fleet\Info;


use Cundd\Fleet\Constants;

class FleetService
{
    /**
     * @return array
     */
    public function getInformation()
    {
        return [
            'protocol'  => Constants::PROTOCOL_VERSION,
            'extensionVersion' => Constants::EXTENSION_VERSION,
        ];
    }
}