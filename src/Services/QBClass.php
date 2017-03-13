<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Client;

class QBClass extends Service {
    /**
     * Name of this service. Must correspond to actual objecet type in Quickbooks.
     * @var string
     */
    protected static $resource = 'class';

    /**
     * Resource name of this service. Must correspond to actual objecet type in Quickbooks and in all lowercase.
     * @var string
     */
    protected static $entity = 'Class';
    
}