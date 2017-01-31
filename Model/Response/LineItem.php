<?php

namespace LAShowroom\TaxJarBundle\Model\Response;

class LineItem
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
     * @var string
     */
    private $id;

    /**
     * @param TaxDetail        $totalTax
     * @param TaxDetail        $stateTax
     * @param TaxDetail        $countyTax
     * @param TaxDetail        $cityTax
     * @param TaxDetail        $specialDistrictTax
     * @param string           $id
     */
    public function __construct(TaxDetail $totalTax, TaxDetail $stateTax, TaxDetail $countyTax, TaxDetail $cityTax, TaxDetail $specialDistrictTax, $id)
    {
        $this->totalTax = $totalTax;
        $this->stateTax = $stateTax;
        $this->countyTax = $countyTax;
        $this->cityTax = $cityTax;
        $this->specialDistrictTax = $specialDistrictTax;
        $this->id = $id;
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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
