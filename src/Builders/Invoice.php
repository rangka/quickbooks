<?php

namespace Rangka\Quickbooks\Builders;

use Rangka\Quickbooks\Builders\Traits\HasCustomer;
use Rangka\Quickbooks\Builders\Traits\Itemizable;

class Invoice extends Builder
{
    use HasCustomer, Itemizable;

    /**
     * Set Tax Code Reference ID.
     *
     * @param string $code Tax Code Reference ID
     *
     * @return Invoice
     */
    public function setTaxCodeRef($code)
    {
        $this->data['TxnTaxDetail']['TxnTaxCodeRef']['value'] = $code;

        return $this;
    }

    /**
     * Set discount percentage.
     *
     * @param float $percent Discount percentage
     *
     * @return Invoice
     */
    public function setDiscountPercent($percent)
    {
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
     *
     * @return Invoice
     */
    public function setDiscountValue($value)
    {
        $discount = $this->client->getItemBuilder()
            ->asDiscount()
            ->setValue($value);

        $this->addItem($discount);

        return $this;
    }

    /**
     * Set Billing Address by ID.
     *
     * @param string $id Address ID
     *
     * @return Invoice
     */
    public function setBillingAddressId($id)
    {
        $this->data['BillAddr']['Id'] = $id;

        return $this;
    }

    /**
     * Set Shipping Address by ID.
     *
     * @param string $id Address ID
     *
     * @return Invoice
     */
    public function setShippingAddressId($id)
    {
        $this->data['ShipAddr']['Id'] = $id;

        return $this;
    }

    /**
     * Set Amount to be Taxed. Only for updates.
     * Creation of invoice will calculate this automatically.
     *
     * @param float  $amount Amount to be taxed.
     * @param string $id     TaxRateRef ID.
     *
     * @return Invoice
     */
    public function addTaxableAmount($amount, $id)
    {
        $this->data['TxnTaxDetail']['TaxLine'] = [
            [
                'DetailType'    => 'TaxLineDetail',
                'TaxLineDetail' => [
                    'TaxRateRef' => [
                        'value' => $id,
                    ],
                    'NetAmountTaxable' => $amount,
                ],
            ],
        ];

        return $this;
    }
}
