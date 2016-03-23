<?php

namespace Rangka\Quickbooks\Builders;

interface BuilderContract {
    /**
    * Return data in JSON format.
    * 
    * @return string
    */
    public function toJson();

    /**
    * Format data in array.
    * 
    * @return string
    */
    public function toArray();
}