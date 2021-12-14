<?php

namespace Cundd\Fleet\Info;

interface ServiceInterface
{
    /**
     * Returns a dictionary of information
     *
     * @return array
     */
    public function getInformation();
}
