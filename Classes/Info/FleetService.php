<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

use Cundd\Fleet\Constants;

class FleetService implements ServiceInterface
{
    public function getInformation(): array
    {
        return [
            'protocol'        => Constants::PROTOCOL_VERSION,
            'providerVersion' => Constants::PROVIDER_VERSION,
            'providerName'    => Constants::PROVIDER_NAME,
        ];
    }
}
