<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Client;
use Rangka\Quickbooks\Query;

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

    /**
    * Query quickbooks. Use Query to construct the query itself.
    *
    * @param \Rangka\Quickbooks\Query   $query      Query object
    * @return object
    */
    public function query() {
        return (new Query($this))->entity(static::$name);
    }

    /**
    * Get all items of this Entity.
    * 
    * @return object
    */
    public function all() {
        return $this->query()->get();
    }
}