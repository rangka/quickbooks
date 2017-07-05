<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Builders\Address;
use Rangka\Quickbooks\Services\Traits\Attachable;

class Customer extends Service
{
    use Attachable;

    /**
     * Get an instance of Address Builder to build Address.
     *
     * @return \Rangka\Quickbooks\Builders\Address
     */
    public function getAddressBuilder()
    {
        return new Address();
    }
}
