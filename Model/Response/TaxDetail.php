<?php

namespace LAShowroom\TaxJarBundle\Model\Response;

class TaxDetail
{
    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $taxRate;

    /**
     * @var float
     */
    private $collectable;

    /**
     * @param float $amount
     * @param float $taxRate
     * @param float $collectable
     */
    public function __construct($amount, $taxRate, $collectable)
    {
        $this->amount = $amount;
        $this->taxRate = $taxRate;
        $this->collectable = $collectable;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * @return float
     */
    public function getCollectable()
    {
        return $this->collectable;
    }
}
