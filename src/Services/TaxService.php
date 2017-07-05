<?php

namespace ReneDeKat\Quickbooks\Services;

class TaxService extends Service
{
    /**
     * Resource endpoint of this service.
     *
     * @var string
     */
    protected static $resource = 'taxservice/taxcode';

    /**
     * Some resource endpoint sends response without root. Set this to false when that happens.
     *
     * @var bool
     */
    protected $responseHasRoot = false;
}
