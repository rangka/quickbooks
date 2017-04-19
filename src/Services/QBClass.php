<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Client;

class QBClass extends Service {
    /**
     * Resource endpoint of this service.
     * 
     * @var string
     */
    protected static $resource = 'class';

    /**
     * Entity name of this service. Must correspond to actual object type in Quickbooks.
     * 
     * @var string
     */
    protected static $entity = 'Class';
    
}