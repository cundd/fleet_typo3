<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 13/04/2017
 * Time: 20:50
 */

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
