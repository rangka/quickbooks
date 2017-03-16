<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Builders\Address;
use Rangka\Quickbooks\Client;
use Rangka\Quickbooks\Services\Traits\Attachable;

class Customer extends Service {
	use Attachable;

    /**
    * Get an instance of Address Builder to build Adrdress
    * @return \Rangka\Quickbooks\Builders\Address
    */
    public function getAddressBuilder() {
        return new Address;
    }
}