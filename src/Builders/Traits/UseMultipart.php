<?php

namespace ReneDeKat\Quickbooks\Builders\Traits;

trait UseMultipart
{
    /**
     * Parts.
     *
     * @var array
     */
    protected $parts = [];

    /**
     * Add a part.
     *
     * @param string $name Name of this field.
     * @param array  $body Content. Must be in array.
     *
     * @return void
     */
    public function addJsonPart($name, $body)
    {
        $this->parts[] = [
            'name'     => $name,
            'contents' => json_encode($body),
            'headers'  => [
                'Content-Type' => 'application/json',
            ],
        ];
    }

    /**
     * Add a part.
     *
     * @param string      $name     Name of this field.
     * @param string      $filePath Path to file.
     * @param string|null $fileType
     * @param string|null $fileName File name. Optional.
     *
     * @return void
     */
    public function addFilePart($name, $filePath, $fileType = null, $fileName = null)
    {
        $part = [
            'name'     => $name,
            'contents' => fopen($filePath, 'r'),
        ];

        if ($fileType) {
            $part['headers']['Content-Type'] = $fileType;
        }

        if ($fileName) {
            $part['filename'] = $fileName;
        }

        $this->parts[] = $part;
    }

    /**
     * Get all parts.
     *
     * @return array
     */
    public function getParts()
    {
        return $this->parts;
    }
}
