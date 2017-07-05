<?php

namespace ReneDeKat\Quickbooks\Builders;

abstract class Builder implements BuilderContract
{
    /**
     * Carries the entire data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Holds client.
     *
     * @var \ReneDeKat\Quickbooks\Client
     */
    protected $client;

    /**
     * Create a builder instance.
     *
     * @param \ReneDeKat\Quickbooks\Client $client Client to connect to Quickbooks
     *
     * @return void
     */
    public function __construct($client = null)
    {
        $this->client = $client;
    }

    /**
     * Set's a value directly to root of array.
     *
     * @param string $name      Method name that was called.
     * @param array  $arguments Arguments that was sent in with the call.
     *
     * @return Builder
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) == 'set') {
            $prop = substr($name, 3);
            $this->data[$prop] = reset($arguments);

            return $this;
        } elseif (substr($name, 0, 3) == 'get') {
            $prop = substr($name, 3);

            if (isset($this->data[$prop])) {
                return $this->data[$prop];
            }
        }

        throw new \Exception('Calling undefined method in '.get_called_class());
    }

    /**
     * Create data on Quickbooks.
     *
     * @return object
     */
    public function create()
    {
        return $this->client->create($this);
    }

    /**
     * Update data on Quickbooks.
     *
     * @return object
     */
    public function update()
    {
        // In order to update an entity, we need to have the complete data. Otherwise, unprovided data will be removed.
        $existing = $this->client->load($this->getId());

        // add existing data
        foreach ($existing as $key => $value) {
            if (!isset($this->data[$key])) {
                $this->data[$key] = $value;
            }
        }

        return $this->client->update($this);
    }

    /**
     * Return data in JSON format.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Format data in array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Get Entity Name.
     *
     * @return string
     */
    public function getEntityName()
    {
        $fullClass = get_called_class();
        $exploded = explode('\\', $fullClass);

        return end($exploded);
    }

    /**
     * Get the name of this service.
     *
     * @return string
     */
    public function getResourceName()
    {
        return static::$name ?: strtolower($this->getEntityName());
    }

    /**
     * @param $syncToken
     * @return $this
     */
    public function setSynctoken($syncToken)
    {
        $this->data['SyncToken'] = $syncToken;

        return $this;
    }
}
