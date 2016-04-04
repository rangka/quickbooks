<?php

namespace Rangka\Quickbooks\Builders;

use Rangka\Quickbooks;
use Rangka\Quickbooks\Services;

class Invoice extends Builder {
    /**
    * Set Customer's ID
    *
    * @param integer $id Customer's Quickbooks ID
    * @return \Rangka\Quickbooks\Builders\Invoice
    */
    public function setCustomer($id) {
        $this->data['CustomerRef']['value'] = $id;

        return $this;
    }

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
    * Add an invoice item. Items must be built with InvoiceItem.
    *
    * @param \Rangka\Quickbooks\Builders\InvoiceItem $item Invoice Item object.
    * @return \Rangka\Quickbooks\Builders\Invoice
    */
    public function addItem(InvoiceItem $item) {
        $this->data['Line'][] = $item->toArray();

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