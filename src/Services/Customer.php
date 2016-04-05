<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Builders\Address;
use Rangka\Quickbooks\Client;

class Customer extends Service {
    /**
     * Name of this service.
     * @var string
     */
    protected static $name = 'customer';

    /**
    * Get an instance of Address Builder to build Adrdress
    * @return \Rangka\Quickbooks\Builders\Address
    */
    public function getAddressBuilder() {
        return new Address;
    }
}