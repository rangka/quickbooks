<?php

namespace Rangka\Quickbooks\Builders\Items;

class Payment extends Item
{
    /**
     * Set an ID and Type of this Payment Item.
     *
     * @param $type
     * @param $id
     *
     * @return Payment
     */
    protected function setId($type, $id)
    {
        $this->data['LinkedTxn'][] = [
            'TxnId'   => $id,
            'TxnType' => $type,
        ];

        return $this;
    }

    /**
     * Set Expense ID for this Payment Item.
     *
     * @param $id
     *
     * @return Payment
     */
    public function setExpenseId($id)
    {
        return $this->setId('Expense', $id);
    }

    /**
     * Set Check ID for this Payment Item.
     *
     * @param $id
     *
     * @return Payment
     */
    public function setCheckId($id)
    {
        return $this->setId('Check', $id);
    }

    /**
     * Set CreditCardCredit ID for this Payment Item.
     *
     * @param $id
     *
     * @return Payment
     */
    public function setCreditCardCreditId($id)
    {
        return $this->setId('CreditCardCredit', $id);
    }

    /**
     * Set JournalEntry ID for this Payment Item.
     *
     * @param $id
     *
     * @return Payment
     */
    public function setJournalEntryId($id)
    {
        return $this->setId('JournalEntry', $id);
    }

    /**
     * Set CreditMemo ID for this Payment Item.
     *
     * @param $id
     *
     * @return Payment
     */
    public function setCreditMemoId($id)
    {
        return $this->setId('CreditMemo', $id);
    }

    /**
     * Set Invoice ID for this Payment Item.
     *
     * @param $id
     *
     * @return Payment
     */
    public function setInvoiceId($id)
    {
        return $this->setId('Invoice', $id);
    }
}
