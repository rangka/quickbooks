<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Builders\InvoiceItem;
use Rangka\Quickbooks\Client;

class Invoice extends Service {
    /**
     * Name of this service.
     * @var string
     */
    protected static $name = 'invoice';

    /**
    * Get an instance of Item Builder to build Invoice's Items
    * @return 
    */
    public function getItemBuilder() {
        return new InvoiceItem;
    }
}