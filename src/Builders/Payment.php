<?php

namespace ReneDeKat\Quickbooks\Builders;

use ReneDeKat\Quickbooks\Builders\Traits\HasCustomer;
use ReneDeKat\Quickbooks\Builders\Traits\Itemizable;

class Payment extends Builder
{
    use HasCustomer, Itemizable;

    /**
     * Alias to setTotalAmt().
     *
     * @param float $amount Payment amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        return $this->setTotalAmt($amount);
    }

    /**
     * Alias to setPaymentRefNum().
     *
     * @param string $num Reference number.
     *
     * @return Payment
     */
    public function setPaymentReference($num)
    {
        return $this->setPaymentRefNum($num);
    }

    /**
     * Set payment method by ID.
     *
     * @param string $id Payment Method ID.
     *
     * @return Payment
     */
    public function setPaymentMethod($id)
    {
        $this->data['PaymentMethodRef']['value'] = $id;

        return $this;
    }

    /**
     * Set Deposit Account by ID.
     *
     * @param string $id Account ID.
     *
     * @return Payment
     */
    public function setDepositAccount($id)
    {
        $this->data['DepositToAccountRef']['value'] = $id;

        return $this;
    }

    /**
     * Alias to setTxnDate().
     *
     * @param string $date Date in format YYYY-MM-DD (2016-12-30)
     *
     * @return Payment
     */
    public function setTransactionDate($date)
    {
        return $this->setTxnDate($date);
    }

    /**
     * Override Builder's toArray() to accumulate Total Amount if not provided.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();

        if (!isset($data['TotalAmt'])) {
            $data['TotalAmt'] = 0;

            foreach ($data['Line'] as $line) {
                $data['TotalAmt'] += $line['Amount'];
            }
        }

        return $data;
    }
}
