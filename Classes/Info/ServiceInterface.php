<?php

declare(strict_types=1);

namespace Cundd\Fleet\Info;

interface ServiceInterface
{
    /**
     * Return a dictionary of information
     *
     * @return array
     */
    public function getInformation(): array;
}
