<?php

namespace Rangka\Quickbooks\Builders;

use Rangka\Quickbooks\Builders\Traits\UseMultipart;

class Attachable extends Builder
{
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
     * @return \stdClass
     */
    public function upload()
    {
        return $this->client->request('POST', 'upload', $this)->AttachableResponse;
    }

    /**
     * Add file to be uploaded.
     *
     * @param array $file     Consists of:
     *                        - 'path' - Path to file. Required.
     *                        - 'type' - Filetype. Required.
     *                        - 'name' - Filename. Optional.
     * @param array $entities Consists of an array of assiociative array containing:
     *                        - 'entity' - Entity type to attach this file to.
     *                        - 'id' - ID of entity.
     *                        - 'includeOnSend' - Attach to Email.
     *                        This is optional.
     *
     * @return void
     */
    public function addFile($file, $entities = [])
    {
        // Add File
        $this->addFilePart('file_content_'.$this->count, $file['path'], isset($file['type']) ? $file['type'] : null, isset($file['name']) ? $file['name'] : null);

        $jsonData = [
            'FileName' => $file['name'],
        ];

        // Only add entities if defined
        if ($entities) {
            $ref = [];

            // Go through each of them and add to our JSON
            foreach ($entities as $entity) {
                $temp['EntityRef'] = [
                    'type'  => $entity['entity'],
                    'value' => $entity['id'],
                ];

                // Only flag `include on send` when it is provided (regardless of value).
                if (isset($entity['includeOnSend'])) {
                    $temp['IncludeOnSend'] = $entity['includeOnSend'];
                }

                $ref[] = $temp;
            }

            $jsonData['AttachableRef'] = $ref;
        }

        if (isset($file['type'])) {
            $jsonData['ContentType'] = $file['type'];
        }

        // Add JSON metadata
        $this->addJsonPart('file_metadata_'.$this->count, $jsonData);

        // Increase counter
        $this->count++;
    }
}
