<?php

namespace ReneDeKat\Quickbooks\Builders\Traits;

trait HasCustomer
{
    /**
     * Set Customer's ID.
     *
     * @param int $id Customer's Quickbooks ID
     *
     * @return $this
     */
    public function setCustomer($id)
    {
        $this->data['CustomerRef']['value'] = $id;

        return $this;
    }
}
