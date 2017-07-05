<?php

namespace ReneDeKat\Quickbooks\Builders\Traits;

use ReneDeKat\Quickbooks\Builders\Items\Item;

trait Itemizable
{
    /**
     * Add an item.
     *
     * @param Item $item Object that extends from Items\Item.
     *
     * @return $this
     */
    public function addItem(Item $item)
    {
        $this->data['Line'][] = $item->toArray();

        return $this;
    }

    /**
     * Get Itemized Item Builder.
     *
     * @return Item
     */
    public function getItemBuilder()
    {
        $class = '\ReneDeKat\Quickbooks\Builders\Items\\'.$this->getEntityName();

        return new $class($this);
    }
}
