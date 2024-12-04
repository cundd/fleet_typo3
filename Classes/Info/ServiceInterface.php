<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

interface ServiceInterface
{
    /**
     * Return a dictionary of information
     *
     * @return array<string,mixed>
     */
    public function getInformation(): array;
}
