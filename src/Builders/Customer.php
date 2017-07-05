<?php

namespace Rangka\Quickbooks\Builders;

class Customer extends Builder
{
    /**
     * Set a customer's full name.
     *
     * @param string $givenName  Customer's given (or first) name.
     * @param string $middleName Customer's middle name.
     * @param string $familyName Customer's family name (or surname).
     *
     * @return Customer
     */
    public function setName($givenName, $middleName = null, $familyName = null)
    {
        $this->data['GivenName'] = $givenName;
        $this->data['MiddleName'] = $middleName;
        $this->data['FamilyName'] = $familyName;

        return $this;
    }

    /**
     * Set customer's address.
     *
     * @param Address $address
     *
     * @return Customer
     */
    public function setBillingAddress(Address $address)
    {
        $this->data['BillAddr'] = $address->toArray();

        return $this;
    }

    /**
     * Set primary phone number. A shorted alias to setPrimaryPhone().
     *
     * @param $phone
     *
     * @return Customer
     */
    public function setPhone($phone)
    {
        $this->setPrimaryPhone($phone);

        return $this;
    }

    /**
     * Set primary phone number.
     *
     * @param $phone
     *
     * @return Customer
     */
    public function setPrimaryPhone($phone)
    {
        $this->data['PrimaryPhone']['FreeFormNumber'] = $phone;

        return $this;
    }

    /**
     * Set mobile phone number.
     *
     * @param $phone
     *
     * @return Customer
     */
    public function setMobilePhone($phone)
    {
        $this->data['Mobile']['FreeFormNumber'] = $phone;

        return $this;
    }

    /**
     * Set fax phone number.
     *
     * @param $phone
     *
     * @return Customer
     */
    public function setFaxPhone($phone)
    {
        $this->data['Fax']['FreeFormNumber'] = $phone;

        return $this;
    }

    /**
     * Set customer's email address.
     *
     * @param $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->data['PrimaryEmailAddr']['Address'] = $email;

        return $this;
    }
}
