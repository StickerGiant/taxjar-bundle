<?php

namespace LAShowroom\TaxJarBundle\Model;

use Webmozart\Assert\Assert;

class LineItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $quantity;

    /**
     * @var string
     */
    private $productTaxCode;

    /**
     * @var float
     */
    private $unitPrice;

    /**
     * @var float
     */
    private $discount;

    /**
     * @param string $id
     * @param string $quantity
     * @param string $productTaxCode
     * @param float  $unitPrice
     * @param float  $discount
     */
    public function __construct($id, $quantity, $productTaxCode, $unitPrice, $discount = 0.0)
    {
        Assert::integer($quantity);
        Assert::float($unitPrice);
        Assert::float($discount);

        $this->id = $id;
        $this->quantity = $quantity;
        $this->productTaxCode = $productTaxCode;
        $this->unitPrice = $unitPrice;
        $this->discount = $discount;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getProductTaxCode()
    {
        return $this->productTaxCode;
    }

    /**
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'quantity' => $this->getQuantity(),
            'product_tax_code' => $this->getProductTaxCode(),
            'unit_price' => $this->getUnitPrice(),
            'discount' => $this->getDiscount(),
        ];
    }
}
