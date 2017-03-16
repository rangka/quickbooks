<?php

namespace Rangka\Quickbooks\Builders;

use Rangka\Quickbooks\Builders\Traits\UseMultipart;

class Attachable extends Builder {
    use UseMultipart;

    /**
     * Number of attached files. Starts with 1.
     *
     * @var int
     */
    protected $count = 1;

    /**
     * Upload attachments to Quickbooks.
     * 
     * @return stdClass
     */
    public function upload() {
        return $this->client->request('POST', 'upload', $this)->AttachableResponse;
    }

    /**
     * Add file to be uploaded.
     *
     * @param array  $file    Consists of:
     *                           - 'path' - Path to file.
     *                           - 'name' - Filename. Optional.
     * @param string $entity  Entity type to attach this file to.
     * @param int    $id      ID of entity.
     * 
     * @return void
     */
    public function addFile($file, $entity = null, $id = null)
    {
        $this->addFilePart('file_content_' . $this->count, $file['path'], $file['type'], isset($file['name']) ? $file['name'] : null);
        $this->addJsonPart('file_metadata_' . $this->count, [
            'AttachableRef' => [
                [
                    'EntityRef' => [
                        'type'  => $entity,
                        'value' => $id,
                    ],
                ]
            ],
            'FileName'    => $file['name'],
            'ContentType' => $file['type'],
       ]);

       $this->count++;
    }
}