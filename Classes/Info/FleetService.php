<?php

namespace Cundd\Fleet\Info;

use Cundd\Fleet\Constants;

class FleetService implements ServiceInterface
{
    public function getInformation()
    {
        return [
            'protocol'        => Constants::PROTOCOL_VERSION,
            'providerVersion' => Constants::PROVIDER_VERSION,
            'providerName'    => Constants::PROVIDER_NAME,
        ];
    }
}
