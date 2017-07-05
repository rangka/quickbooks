<?php

namespace Rangka\Quickbooks\Services;

class Preferences extends Service {

    /**
     * Load a single item
     *
     * @param $id
     * @return
     */
    public function load($id) {
        return parent::get($this->getResourceName())->{$this->getEntityName()};
    }
  
}
