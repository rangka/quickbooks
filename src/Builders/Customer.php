<?php

namespace Rangka\Quickbooks\Builders;

use Rangka\Quickbooks\Builders\Address;

class Customer extends Builder {
    /**
     * Set a customer's full name
     * @param string $givenName  Customer's given (or first) name.
     * @param string $middleName Customer's middle name.
     * @param string $familyName Customer's family name (or surname).
     */
    public function setName($givenName, $middleName = null, $familyName = null) {
        $this->data['GivenName'] = $givenName;
        $this->data['MiddleName'] = $middleName;
        $this->data['FamilyName'] = $familyName;

        return $this;
    }

    /**
    * Set customer's addresss.
    * 
    * @return void
    */
    public function setBillingAddress(Address $address) {
        $this->data['BillAddr'] = $address->toArray();

        return $this;
    }

    /**
    * Set primary phone number. A shorted alias to setPrimaryPhone()
    * 
    * @return void
    */
    public function setPhone($phone) {
        $this->setPrimaryPhone($phone);

        return $this;
    }

    /**
    * Set primary phone number.
    * 
    * @return Rangka\Quickbooks\Builders\Customer
    */
    public function setPrimaryPhone($phone) {
        $this->data['PrimaryPhone']['FreeFormNumber'] = $phone;

        return $this;
    }

    /**
    * Set mobile phone numner.
    * 
    * @return Rangka\Quickbooks\Builders\Customer
    */
    public function setMobilePhone($phone) {
        $this->data['Mobile']['FreeFormNumber'] = $phone;

        return $this;
    }

    /**
    * Set fax phone numner.
    * 
    * @return Rangka\Quickbooks\Builders\Customer
    */
    public function setFaxPhone($phone) {
        $this->data['Fax']['FreeFormNumber'] = $phone;

        return $this;
    }

    /**
    * Set customer's email address.
    * 
    * @return Rangka\Quickbooks\Builders\Customer
    */
    public function setEmail($email) {
        $this->data['PrimaryEmailAddr']['Address'] = $email;

        return $this;
     }
}