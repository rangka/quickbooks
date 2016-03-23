<?php

namespace Rangka\Quickbooks\Builders;

use Rangka\Quickbooks;
use Rangka\Quickbooks\Services;

abstract class Builder implements BuilderContract {
    /**
     * Carries the entire data.
     * 
     * @var array
     */
    protected $data = [];

    /**
    * Set's a value directly to root of array.
    * @param  string $name      Method name that was called.
    * @param  array  $arguments Arguments that was sent in with the call.
    * @return void
    */
    public function __call($name, $arguments) {
        if (substr($name, 0, 3) == 'set') {
            $prop = substr($name, 3);
            $this->data[$prop] = reset($arguments);

            return $this;
        }

        throw new \Exception("Calling undefined method in " . get_called_class());
    }

    /**
    * Return data in JSON format.
    * 
    * @return void
    */
    public function toJson() {
        return json_encode($this->toArray());
    }

    /**
    * Format data in array.
    * 
    * @return void
    */
    public function toArray() {
        return $this->data;
    }
}