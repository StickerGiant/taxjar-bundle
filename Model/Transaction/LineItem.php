<?php

namespace LAShowroom\TaxJarBundle\Model\Transaction;

use LAShowroom\TaxJarBundle\Model\LineItem as BaseLineItem;
use Webmozart\Assert\Assert;

class LineItem extends BaseLineItem
{
    /**
     * @var string
     */
    private $productIdentifier;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $salesTax;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getProductIdentifier()
    {
        return $this->productIdentifier;
    }

    /**
     * @param string $productIdentifier
     */
    public function setProductIdentifier($productIdentifier)
    {
        $this->productIdentifier = $productIdentifier;
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
}
