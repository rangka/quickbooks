<?php

namespace Rangka\Quickbooks\Builders\Items;

class Invoice extends Item {
    /**
    * Set Item's name. This is not needed if name is set through setItem()
    *
    * @param string $name Name of Item.
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function setUnitPrice($name) {
        $this->data[$this->data['DetailType']]['UnitPrice'] = $name;

        return $this;
    }

    /**
    * Set Item Reference (from Products & Services) associated to this Item.
    *
    * @param string $id Item ID
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function setItemRef($id) {
        $this->data[$this->data['DetailType']]['ItemRef']['value'] = $id;

        return $this;
    }

    /**
    * Set Item's quantity.
    *
    * @param integer $quantity Item quantity.
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function setQuantity($quantity) {
        $this->data[$this->data['DetailType']]['Qty'] =  $quantity;

        return $this;
    }

    /**
    * Set this Item as Sales Item.
    * 
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function asSalesItem() {
        $this->setDetailType('SalesItemLineDetail');

        return $this;
    }

    /**
    * Set this Item as Discount.
    * 
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function asDiscount() {
        $this->setDetailType('DiscountLineDetail');

        return $this;
    }

    /**
    * Set discount's percentage value.
    *
    * @param float $percent Discount percentage.
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function setPercent($percent) {
        $this->data[$this->data['DetailType']]['PercentBased'] = true;
        $this->data[$this->data['DetailType']]['DiscountPercent'] = $percent;

        return $this;
    }

    /**
    * Set discount's value.
    *
    * @param float $value Discount value.
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function setValue($value) {
        $this->setAmount($value);
        $this->data[$this->data['DetailType']]['PercentBased'] = false;

        return $this;
    }

    /**
    * Set this item to be taxable.
    *
    * @param boolean $taxable Set to TRUE to make it taxable or FALSE otherwise. TRUE by default.
    * @return \Rangka\Quickbooks\Builders\InvoiceItem
    */
    public function isTaxable($taxable = true) {
        if ($taxable)
            $this->data[$this->data['DetailType']]['TaxCodeRef']['value'] = 'TAX';
        else if (isset($this->data[$this->data['DetailType']]['TaxCodeRef']))
            unset($this->data[$this->data['DetailType']]['TaxCodeRef']);

        return $this;
    }
}