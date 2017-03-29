<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Client;

class Preferences extends Service {
  
  /**
     * Load a single item
     *
     * @return
     */
    public function load($id) {
        return parent::get($this->getResourceName())->{$this->getEntityName()};
    }
  
}
