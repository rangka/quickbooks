<?php

namespace ReneDeKat\Quickbooks\Services;

use ReneDeKat\Quickbooks\Services\Traits\Attachable;
use ReneDeKat\Quickbooks\Services\Traits\Itemizable;

class Payment extends Service
{
    use Itemizable, Attachable;
}
