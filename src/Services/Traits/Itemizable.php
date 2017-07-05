<?php

namespace Rangka\Quickbooks\Services\Traits;

trait Itemizable {
    /**
     * Get Itemized Item Builder.
     * 
     * @return \Rangka\Quickbooks\Builders\Items\Invoice|\Rangka\Quickbooks\Builders\Items\Payment
     */
    public function getItemBuilder() {
        $class = '\Rangka\Quickbooks\Builders\Items\\' . $this->getEntityName();
        return new $class($this);
    }
}
