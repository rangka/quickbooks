<?php

namespace Rangka\Quickbooks;

class Query {
    /**
     * Holds the client or any Services that utilizes it.
     * 
     * @var \Rangka\Quickbooks\Client
     */
    protected $client;

    /**
     * Entity name
     * 
     * @var string
     */
    protected $entity;

    /**
     * Properties to select
     * @var array[string]
     */
    protected $properties;

    /**
     * WHERE constraints.
     * 
     * @var array
     */
    protected $where;

    /**
     * Consstruct a new query.
     *
     * @return void
     */
    public function __construct($client) {
        $this->client = $client;
    }

    /**
     * Dumps query statment once casted to string.
     * 
     * @return string
     */
    public function __toString() {
        return $this->generate();
    }

    /**
     * Generate full SQL statement.
     * 
     * @return string
     */
    public function generate() {
        $select = $this->properties ? implode(', ', $this->properties) : '*';

        $sql = [
            'SELECT',
            $select,
            'FROM',
            $this->entity
        ];


        if ($this->where) {
            $sql[] = 'WHERE';
            $sql[] = implode(' AND ', $this->where);
        }

        return implode(' ', $sql);
    }

    /**
     * Set entity to request.
     *
     * @param    string                      $entity     Entity name.
     * @return   \Rangka\Quickbooks\Query
     */
    public function entity($entity) {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Set properties to retrieve
     *
     * @param    array[string]               $properties     Array of properties.
     * @return   \Rangka\Quickbooks\Query
     */
    public function select($properties) {
        if (!is_array($properties))
            $properties = [$properties];

        $this->properties = $properties;

        return $this;
    }

    /**
     * Set a where constraint.
     *
     * @param    string                      $property       Property name.
     * @param    string                      $operator       Operator for constraining. Either `<`, `<=`, `>`, `>=`, `=`, `!=`, `LIKE`
     * @param    string                      $constraint     Value of constraint
     * @return   \Rangka\Quickbooks\Query
     */
    public function where($property, $operator, $constraint) {
        $this->where[] = $property . " " . $operator . " '" . $constraint . "'";

        return $this;
    }

    /**
     * Set a an IN constraint.
     *
     * @param    string    $property       Property name.
     * @param    array     $array          Array of IDs. 
     * @return   void
     */
    public function in($property, $ids)
    {
        $this->where[] = $property . ' IN (\'' . implode('\',\'', $ids) . '\')';

        return $this;
    }

    /**
     * Set a LIKE constraint.
     *
     * @param string $property Property name.
     * @param string $constraint Constraint value.
     * @return \Rangka\Quickbooks\Query
     */
    public function like($property, $constraint) {
        return $this->where($property, 'LIKE', $constraint);
    }

    /**
     * Get data from Quickbooks
     * 
     * @return array
     */
    public function get() {
        $data = $this->client->get('query?query=' . rawurlencode($this));

        // If Query syntax is incorrect, it will return a 200 with Fault.
        // Lets make that an Exception instead.
        if (property_exists($data, 'Fault')) {
            throw new \Exception('[' . $data->Fault->Error[0]->code . ' ' . $data->Fault->Error[0]->Message . '] ' . $data->Fault->Error[0]->Detail);
        }

        $data = $data->QueryResponse;

        if (!property_exists($data, $this->entity)) {
            $return = new \stdClass;
            $return->{$this->entity} = [];
            $return->maxResults = 0;
            $return->totalCount = 0;

            return $return;
        }

        return $data;
    }
}