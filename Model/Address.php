<?php

namespace LAShowroom\TaxJarBundle\Model;

class Address
{
    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $country;

    /**
     * @param string $street
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $country
     */
    public function __construct($street, $city, $state, $zip, $country)
    {
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    public function toArray($prefix = '')
    {
        $result = [];

        if (!empty($this->getStreet())) {
            $result[sprintf('%sstreet', $prefix)] = $this->getStreet();
        }

        if (!empty($this->getCity())) {
            $result[sprintf('%scity', $prefix)] = $this->getCity();
        }

        if (!empty($this->getState())) {
            $result[sprintf('%sstate', $prefix)] = $this->getState();
        }

        if (!empty($this->getZip())) {
            $result[sprintf('%szip', $prefix)] = $this->getZip();
        }

        if (!empty($this->getCountry())) {
            $result[sprintf('%scountry', $prefix)] = $this->getCountry();
        }

        return $result;
    }
}
