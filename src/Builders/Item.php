<?php

namespace ReneDeKat\Quickbooks\Builders;

class Item extends Builder
{
    /**
     * Set item's type. Either `Inventory`, `NonInventory` or `Service`.
     * When set as `Inventory`, `TrackQtyOnHand` will automatically
     * be set as true.
     * When set as `NonInventory` or `Service`, `TrackQtyOnHand` will
     * be set as false.
     *
     * @param string $type Item's type. Either `Inventory`, `NonInventory` or `Service`.
     *
     * @return Item
     */
    public function setType($type)
    {
        parent::setType($type);

        $this->setTrackQtyOnHand($type == 'Inventory');

        return $this;
    }

    /**
     * Set Account ref by type.
     *
     * @param string $type Type of Account. Either 'Asset', 'Income' or 'Expense'.
     * @param int    $id   ID of Account Ref.
     * @param string $name Name of Account Ref.
     *
     * @return Item
     */
    public function setAccountRef($type, $id, $name = '')
    {
        $this->data[$type.'AccountRef'] = [
            'value' => $id,
            'name'  => $name,
        ];

        return $this;
    }

    /**
     * Set Income Account.
     *
     * @param int    $id   ID of Account Ref.
     * @param string $name Name of Account Ref.
     *
     * @return Item
     */
    public function setIncomeAccountRef($id, $name = '')
    {
        $this->setAccountRef('Income', $id, $name);

        return $this;
    }

    /**
     * Set Expense Account.
     *
     * @param int    $id   ID of Account Ref.
     * @param string $name Name of Account Ref.
     *
     * @return Item
     */
    public function setExpenseAccountRef($id, $name = '')
    {
        $this->setAccountRef('Expense', $id, $name);

        return $this;
    }

    /**
     * Set Asset Account.
     *
     * @param int    $id   ID of Account Ref.
     * @param string $name Name of Account Ref.
     *
     * @return Item
     */
    public function setAssetAccountRef($id, $name = '')
    {
        $this->setAccountRef('Asset', $id, $name);

        return $this;
    }

    /**
     * Set item's quantity. Only need when type is `Inventory`.
     * Alias for `setQtyOnHand`.
     *
     * @param int $amount Amount of Item
     *
     * @return Item
     */
    public function setQuantity($amount)
    {
        $this->data['QtyOnHand'] = $amount;

        return $this;
    }
}
