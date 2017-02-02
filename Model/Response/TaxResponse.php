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

    /**
     * @var string
     */
    private $userId;

    /**
     * @var \DateTime();
     */
    private $transactionDate;

    /**
     * @var string
     */
    private $transactionReferenceId;

    /**
     * @var float
     */
    private $salesTax;

    public function __construct(\stdClass $response)
    {
        if (!empty($response->order_total_amount)) {
            $this->totalAmount = $response->order_total_amount;
        } elseif(!empty($response->amount)) {
            $this->totalAmount = $response->amount;
        }

        if (!empty($response->shipping)) {
            $this->shipping = $response->shipping;
        }

        if (!empty($response->taxable_amount)) {
            $this->taxableAmount = $response->taxable_amount;
        }

        if (!empty($response->amount_to_collect)) {
            $this->amountToCollect = $response->amount_to_collect;
        }

        if (!empty($response->rate)) {
            $this->rate = $response->rate;
        }

        if (!empty($response->has_nexus)) {
            $this->hasNexus = (bool) $response->has_nexus;
        }

        if (!empty($response->freight_taxable)) {
            $this->freightTaxable = (bool) $response->freight_taxable;
        }

        if (!empty($response->tax_source)) {
            $this->taxSource = $response->tax_source;
        }

        if (!empty($response->breakdown)) {
            $this->taxBreakdown = new TaxBreakdown($response->breakdown);
        }

        if (!empty($response->user_id)) {
            $this->userId = $response->user_id;
        }

        if (!empty($response->transaction_date)) {
            $this->transactionDate = new \DateTime($response->transaction_date);
        }

        if (!empty($response->transaction_reference_id)) {
            $this->transactionReferenceId = $response->transaction_reference_id;
        }

        if (!empty($response->sales_tax)) {
            $this->salesTax = $response->sales_tax;
        }
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

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @return string
     */
    public function getTransactionReferenceId()
    {
        return $this->transactionReferenceId;
    }

    /**
     * @return float
     */
    public function getSalesTax()
    {
        return $this->salesTax;
    }
}
