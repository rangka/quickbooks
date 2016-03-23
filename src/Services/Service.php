<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Client;

class Service extends Client {
    /**
     * Name of this service. Must correspond to actual objecet type in Quickbooks.
     * @var string
     */
    protected static $name = '';

    /**
    * Load a single item
    * @return 
    */
    public function load($id) {
        return parent::get(static::$name . '/' . $id);
    }

    /**
    * Create a single item
    * @param array $options Item information
    * @return 
    */
    public function create($options) {
        return json_decode((string) parent::post(static::$name, $options)->getBody())->{ucwords(static::$name)};
    }
}