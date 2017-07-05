<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Services\Traits\Attachable;
use Rangka\Quickbooks\Services\Traits\Itemizable;

class Payment extends Service {
    use Itemizable, Attachable;
}
