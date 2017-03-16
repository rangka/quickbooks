<?php

namespace Rangka\Quickbooks\Services\Traits;

use Rangka\Quickbooks\Services\Attachable as AttachableService;

trait Attachable {
    /**
     * Attach attachments to an Entity.
     *
     * @param int    $id      ID of entity.
     * @param array  $file    Array of files each consists of:
     *                           - 'path' - Path to file.
     *                           - 'name' - Filename. Optional.
     * 
     * @return \Rangka\Quickbooks\Builders\ItemizedItem
     */
    public function attach($id, $files) {
        $service = new AttachableService;
        $builder = $service->getBuilder();

        foreach ($files as $file) {
            $builder->addFile($file, $this->getEntityName(), $id);
        }

        return $builder->upload();
    }
}