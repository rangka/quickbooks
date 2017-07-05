<?php

namespace ReneDeKat\Quickbooks\Services\Traits;

trait Itemizable
{
    /**
     * Get Itemized Item Builder.
     *
     * @return \ReneDeKat\Quickbooks\Builders\Items\Invoice|\ReneDeKat\Quickbooks\Builders\Items\Payment
     */
    public function getItemBuilder()
    {
        $class = '\ReneDeKat\Quickbooks\Builders\Items\\'.$this->getEntityName();

        return new $class($this);
    }
}
