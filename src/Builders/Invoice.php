<?php

namespace Rangka\Quickbooks\Builders;

use Rangka\Quickbooks\Builders\Traits\HasCustomer;
use Rangka\Quickbooks\Builders\Traits\Itemizable;

class Invoice extends Builder {
    use HasCustomer, Itemizable;

    /**
    * Set Tax Code Reference ID
    *
    * @param string $code Tax Code Reference ID
    * @return \Rangka\Quickbooks\Builders\Invoice
    */
    public function setTaxCodeRef($code) {
        $this->data['TxnTaxDetail']['TxnTaxCodeRef']['value'] = $code;

        return $this;
    }

    /**
    * Set discount percentage.
    *
    * @param float $percent Discount percentage
    * @return \Rangka\Quickbooks\Builders\Invoice
    */
    public function setDiscountPercent($percent) {
        $discount = $this->client->getItemBuilder()
            ->asDiscount()
            ->setPercent($percent);

        $this->addItem($discount);

        return $this;
    }

    /**
    * Set discount value.
    *
    * @param float $percent Discount value
    * @return \Rangka\Quickbooks\Builders\Invoice
    */
    public function setDiscountValue($value) {
        $discount = $this->client->getItemBuilder()
            ->asDiscount()
            ->setValue($value);

        $this->addItem($discount);

        return $this;
    }
}