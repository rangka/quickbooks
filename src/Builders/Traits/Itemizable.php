<?php

namespace Rangka\Quickbooks\Builders\Traits;

use Rangka\Quickbooks\Builders\Items\Item;

trait Itemizable {
    /**
    * Add an item.
    *
    * @param \Rangka\Quickbooks\Builders\Items\Item    $item  Object that extends from Items\Item.
    * @return Current object ($this).
    */
    public function addItem(Item $item) {
        $this->data['Line'][] = $item->toArray();

        return $this;
    }

    /**
     * Get Itemized Item Builder.
     * 
     * @return \Rangka\Quickbooks\Builders\Items\Item
     */
    public function getItemBuilder() {
        $class = '\Rangka\Quickbooks\Builders\Items\\' . $this->getEntityName();
        return new $class($this);
    }
}
