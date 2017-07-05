<?php

namespace ReneDeKat\Quickbooks\Services;

use ReneDeKat\Quickbooks\Builders\Address;
use ReneDeKat\Quickbooks\Services\Traits\Attachable;

class Customer extends Service
{
    use Attachable;

    /**
     * Get an instance of Address Builder to build Address.
     *
     * @return \ReneDeKat\Quickbooks\Builders\Address
     */
    public function getAddressBuilder()
    {
        return new Address();
    }
}
