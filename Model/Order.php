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
     * @var string
     */
    private $transactionId;

    /**
     * @var \DateTime
     */
    private $transactionDate;

    /**
     * @var float
     */
    private $salesTax;

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

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param \DateTime $transactionDate
     */
    public function setTransactionDate(\DateTime $transactionDate)
    {
        $this->transactionDate = $transactionDate;
    }

    /**
     * @return float
     */
    public function getSalesTax()
    {
        return $this->salesTax;
    }

    /**
     * @param float $salesTax
     */
    public function setSalesTax($salesTax)
    {
        Assert::float($salesTax);

        $this->salesTax = $salesTax;
    }

    public function toArray()
    {
        $result = [];

        if (!empty($this->fromAddress)) {
            $result = array_merge($result, $this->fromAddress->toArray('from_'));
        }

        if (!empty($this->toAddress)) {
            $result = array_merge($result, $this->toAddress->toArray('to_'));
        }

        if (!empty($this->salesTax)) {
            $result['sales_tax'] = $this->salesTax;
        }

        $result = array_merge($result, [
            'line_items' => array_map(function(LineItem $elem) {
                return $elem->toArray();
            }, $this->lineItems),
            'amount' => $this->getAmount(),
            'shipping' => $this->getShipping(),
            'nexus_addresses' => array_map(function(Address $elem, $id) {
                return array_merge($elem->toArray(), ['id' => $id, ]);
            }, $this->nexusAddresses, array_keys($this->nexusAddresses)),
        ]);

        if (!empty($this->transactionId)) {
             $result['transaction_id'] = $this->transactionId;
        }

        if (!empty($this->transactionDate)) {
            $result['transaction_date'] = $this->transactionDate->format(\DateTime::ISO8601);
        }

        return $result;
    }

    public function getCacheKey()
    {
        return sprintf('order_%s', md5(json_encode($this->toArray())));
    }
}
