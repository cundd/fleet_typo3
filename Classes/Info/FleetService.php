<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

use Cundd\Fleet\Constants;

/**
 * @phpstan-type FleetInformation array{protocol:string, providerVersion:string, providerName:string}
 */
class FleetService implements ServiceInterface
{
    /**
     * @return FleetInformation
     */
    public function getInformation(): array
    {
        return [
            'protocol'        => Constants::PROTOCOL_VERSION,
            'providerVersion' => Constants::PROVIDER_VERSION,
            'providerName'    => Constants::PROVIDER_NAME,
        ];
    }
}
