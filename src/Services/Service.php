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
        return parent::get(static::$name . '/' . $id)->{ucwords(static::$name)};
    }

    /**
    * Create a single item
    * @param array $data Item information
    * @return 
    */
    public function create($data) {
        return parent::post(static::$name, $data)->{ucwords(static::$name)};
    }

    /**
    * Update an entity.
    *
    * @param array $data Item information.
    * @return void
    */
    public function update($data) {
        return parent::post(static::$name . '?operation=update', $data)->{ucwords(static::$name)};
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

    /**
    * Get builder instance to construct entity data.
    *
    * @return \Rangka\Quickbooks\Builders\Builder
    */
    public function getBuilder() {
        $class = '\Rangka\Quickbooks\Builders\\' . ucwords(static::$name);
        return new $class($this);
    }
}