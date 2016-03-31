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
        return json_decode((string) $this->client->get('query?query=' . rawurlencode($this))->getBody())->QueryResponse;
    }
}