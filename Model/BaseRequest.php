<?php

namespace LAShowroom\TaxJarBundle\Model;

use Webmozart\Assert\Assert;

class BaseRequest
{
    /**
     * @var Address
     */
    protected $fromAddress;

    /**
     * @var Address
     */
    protected $toAddress;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var float
     */
    protected $shipping;

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

    protected function toArray()
    {
        $result = [];

        if (!empty($this->fromAddress)) {
            $result = array_merge($result, $this->fromAddress->toArray('from_'));
        }

        if (!empty($this->toAddress)) {
            $result = array_merge($result, $this->toAddress->toArray('to_'));
        }

        $result = array_merge($result, [
            'amount' => $this->getAmount(),
            'shipping' => $this->getShipping(),
        ]);

        return $result;
    }
}
