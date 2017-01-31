<?php

namespace LAShowroom\TaxJarBundle\Model\Response;

class TaxBreakdown
{
    /**
     * @var TaxDetail
     */
    private $totalTax;

    /**
     * @var TaxDetail
     */
    private $stateTax;

    /**
     * @var TaxDetail
     */
    private $countyTax;

    /**
     * @var TaxDetail
     */
    private $cityTax;

    /**
     * @var TaxDetail
     */
    private $specialDistrictTax;

    /**
     * @var LineItem[]
     */
    private $lineItems = [];

    /**
     * @param \stdClass $response
     */
    public function __construct(\stdClass $response)
    {
        $this->totalTax = new TaxDetail(
            $response->taxable_amount,
            $response->combined_tax_rate,
            $response->tax_collectable
        );
        $this->stateTax = new TaxDetail(
            $response->state_taxable_amount,
            $response->state_tax_rate,
            $response->state_tax_collectable
        );
        $this->countyTax = new TaxDetail(
            $response->county_taxable_amount,
            $response->county_tax_rate,
            $response->county_tax_collectable
        );
        $this->cityTax = new TaxDetail(
            $response->city_taxable_amount,
            $response->city_tax_rate,
            $response->city_tax_collectable
        );
        $this->specialDistrictTax = new TaxDetail(
            $response->special_district_taxable_amount,
            $response->special_tax_rate,
            $response->special_district_tax_collectable
        );

        foreach ($response->line_items as $line_item) {
            $this->lineItems[] = new LineItem(
                new TaxDetail(
                    $line_item->taxable_amount,
                    $line_item->combined_tax_rate,
                    $line_item->tax_collectable
                ),
                new TaxDetail(
                    $line_item->state_taxable_amount,
                    $line_item->state_sales_tax_rate,
                    $line_item->state_amount
                ),
                new TaxDetail(
                    $line_item->county_taxable_amount,
                    $line_item->county_tax_rate,
                    $line_item->county_amount
                ),
                new TaxDetail(
                    $line_item->city_taxable_amount,
                    $line_item->city_tax_rate,
                    $line_item->city_amount
                ),
                new TaxDetail(
                    $line_item->special_district_taxable_amount,
                    $line_item->special_tax_rate,
                    $line_item->special_district_amount
                ),
                $line_item->id
            );
        }
    }

    /**
     * @return TaxDetail
     */
    public function getTotalTax()
    {
        return $this->totalTax;
    }

    /**
     * @return TaxDetail
     */
    public function getStateTax()
    {
        return $this->stateTax;
    }

    /**
     * @return TaxDetail
     */
    public function getCountyTax()
    {
        return $this->countyTax;
    }

    /**
     * @return TaxDetail
     */
    public function getCityTax()
    {
        return $this->cityTax;
    }

    /**
     * @return TaxDetail
     */
    public function getSpecialDistrictTax()
    {
        return $this->specialDistrictTax;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }
}
