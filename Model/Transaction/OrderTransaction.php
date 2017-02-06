<?php

namespace LAShowroom\TaxJarBundle\Model\Transaction;

use LAShowroom\TaxJarBundle\Model\BaseRequest;
use Webmozart\Assert\Assert;

class OrderTransaction extends BaseRequest
{
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
     * @var LineItem[]
     */
    private $lineItems = [];

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

    public function toArray()
    {
        $result = [];

        if (!empty($this->transactionId)) {
            $result['transaction_id'] = $this->transactionId;
        }

        if (!empty($this->transactionDate)) {
            $result['transaction_date'] = $this->transactionDate->format(\DateTime::ISO8601);
        }

        if (!empty($this->salesTax)) {
            $result['sales_tax'] = $this->salesTax;
        }

        $result['line_items'] = array_map(function(LineItem $elem) {
            return $elem->toArray();
        }, $this->lineItems);

        return array_merge(parent::toArray(), $result);
    }
}
