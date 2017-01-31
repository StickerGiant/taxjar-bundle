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

    public function toArray()
    {
        return [
            'country' => $this->getCountry(),
            'zip' => $this->getZip(),
            'state' => $this->getState(),
            'city' => $this->getCity(),
            'street' => $this->getStreet(),
        ];
    }
}
