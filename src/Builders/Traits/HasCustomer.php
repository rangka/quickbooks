<?php

namespace Rangka\Quickbooks\Builders\Traits;

trait HasCustomer {
    /**
    * Set Customer's ID
    *
    * @param integer $id Customer's Quickbooks ID
    * @return $this
    */
    public function setCustomer($id) {
        $this->data['CustomerRef']['value'] = $id;

        return $this;
    }
}
