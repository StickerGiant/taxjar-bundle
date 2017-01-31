<?php

namespace LAShowroom\TaxJarBundle\Model;

use Webmozart\Assert\Assert;

class Order
{
    /**
     * @var Address
     */
    private $fromAddress;

    /**
     * @var Address
     */
    private $toAddress;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $shipping;

    /**
     * @var Address[]
     */
    private $nexusAddresses = [];

    /**
     * @var LineItem[]
     */
    private $lineItems = [];

    /**
     * @return Address
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param Address $fromAddress
     */
    public function setFromAddress(Address $fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return Address
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * @param Address $toAddress
     */
    public function setToAddress(Address $toAddress)
    {
        $this->toAddress = $toAddress;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        Assert::float($amount);

        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param float $shipping
     */
    public function setShipping($shipping)
    {
        Assert::float($shipping);

        $this->shipping = $shipping;
    }

    /**
     * @return Address[]
     */
    public function getNexusAddresses()
    {
        return $this->nexusAddresses;
    }

    /**
     * @param Address[] $nexusAddresses
     */
    public function setNexusAddresses(array $nexusAddresses)
    {
        Assert::allIsInstanceOf($nexusAddresses, Address::class);

        $this->nexusAddresses = $nexusAddresses;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * @param LineItem[] $lineItems
     */
    public function setLineItems(array $lineItems)
    {
        Assert::allIsInstanceOf($lineItems, LineItem::class);

        $this->lineItems = $lineItems;
    }

    public function addLineItem(LineItem $lineItem)
    {
        $this->lineItems[] = $lineItem;
    }

    public function addNexusAddress($id, Address $address)
    {
        $this->nexusAddresses[$id] = $address;
    }

    public function toArray()
    {
        return [
            'from_street' => $this->fromAddress->getStreet(),
            'from_city' => $this->fromAddress->getCity(),
            'from_state' => $this->fromAddress->getState(),
            'from_zip' => $this->fromAddress->getZip(),
            'from_country' => $this->fromAddress->getCountry(),
            'to_street' => $this->toAddress->getStreet(),
            'to_city' => $this->toAddress->getCity(),
            'to_state' => $this->toAddress->getState(),
            'to_zip' => $this->toAddress->getZip(),
            'to_country' => $this->toAddress->getCountry(),
            'line_items' => array_map(function(LineItem $elem) {
                return $elem->toArray();
            }, $this->lineItems),
            'amount' => $this->getAmount(),
            'shipping' => $this->getShipping(),
            'nexus_addresses' => array_map(function(Address $elem, $id) {
                return array_merge($elem->toArray(), ['id' => $id, ]);
            }, $this->nexusAddresses, array_keys($this->nexusAddresses)),
        ];
    }
}
