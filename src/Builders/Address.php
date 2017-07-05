<?php

namespace ReneDeKat\Quickbooks\Builders;

class Address extends Builder
{

    /**
     * @param string $line1
     * @return Address
     */
    public function setLine1($line1)
    {
        $this->data['Line1'] = $line1;

        return $this;
    }

    /**
     * @param string $line2
     * @return Address
     */
    public function setLine2($line2)
    {
        $this->data['Line2'] = $line2;

        return $this;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->data['City'] = $city;

        return $this;
    }

    /**
     * @param string $postalCode
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->data['PostalCode'] = $postalCode;

        return $this;
    }

    /**
     * @param string $country
     * @return Address
     */
    public function setCountry($country)
    {
        $this->data['Country'] = $country;

        return $this;
    }
}
