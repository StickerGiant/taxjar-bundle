<?php

namespace LAShowroom\TaxJarBundle\Model\Response;

class TaxResponse
{
    /**
     * @var float
     */
    private $totalAmount;

    /**
     * @var float
     */
    private $shipping;

    /**
     * @var float
     */
    private $taxableAmount;

    /**
     * @var float
     */
    private $amountToCollect;

    /**
     * @var float
     */
    private $rate;

    /**
     * @var bool
     */
    private $hasNexus;

    /**
     * @var bool
     */
    private $freightTaxable;

    /**
     * @var string
     */
    private $taxSource;

    /**
     * @var TaxBreakdown
     */
    private $taxBreakdown;

    public function __construct(\stdClass $response)
    {
        $this->totalAmount = $response->order_total_amount;
        $this->shipping = $response->shipping;
        $this->taxableAmount = $response->taxable_amount;
        $this->amountToCollect = $response->amount_to_collect;
        $this->rate = $response->rate;
        $this->hasNexus = (bool) $response->has_nexus;
        $this->freightTaxable = (bool) $response->freight_taxable;
        $this->taxSource = $response->tax_source;
        $this->taxBreakdown = new TaxBreakdown($response->breakdown);
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return float
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @return float
     */
    public function getTaxableAmount()
    {
        return $this->taxableAmount;
    }

    /**
     * @return float
     */
    public function getAmountToCollect()
    {
        return $this->amountToCollect;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @return bool
     */
    public function hasNexus()
    {
        return $this->hasNexus;
    }

    /**
     * @return bool
     */
    public function isFreightTaxable()
    {
        return $this->freightTaxable;
    }

    /**
     * @return string
     */
    public function getTaxSource()
    {
        return $this->taxSource;
    }

    /**
     * @return TaxBreakdown
     */
    public function getTaxBreakdown()
    {
        return $this->taxBreakdown;
    }
}
