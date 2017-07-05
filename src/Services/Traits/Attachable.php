<?php

namespace ReneDeKat\Quickbooks\Services\Traits;

use ReneDeKat\Quickbooks\Services\Attachable as AttachableService;

trait Attachable
{
    /**
     * Attach attachments to an Entity.
     *
     * @param int   $id            ID of entity.
     * @param array $files         Array of files each consists of:
     *                             - 'path' - Path to file. Required.
     *                             - 'name' - File name. Optional.
     *                             - 'type' - File type. Optional.
     * @param bool  $includeOnSend Attach to Email. Optional.
     *
     * @return \stdClass
     */
    public function attach($id, $files, $includeOnSend = null)
    {
        $service = new AttachableService();

        /** @var \ReneDeKat\Quickbooks\Builders\Attachable $builder */
        $builder = $service->getBuilder();

        foreach ($files as $file) {
            $builder->addFile($file, [[
                'entity'        => $this->getEntityName(),
                'id'            => $id,
                'includeOnSend' => $includeOnSend,
            ]]);
        }

        return $builder->upload();
    }
}
