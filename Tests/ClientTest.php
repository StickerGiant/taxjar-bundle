<?php

namespace LAShowroom\TaxJarBundle\Tests;

use LAShowroom\TaxJarBundle\Client;
use LAShowroom\TaxJarBundle\Model\Address;
use LAShowroom\TaxJarBundle\Model\LineItem;
use LAShowroom\TaxJarBundle\Model\Order;
use LAShowroom\TaxJarBundle\Model\Response\TaxBreakdown;
use LAShowroom\TaxJarBundle\Model\Response\TaxDetail;
use LAShowroom\TaxJarBundle\Model\Response\TaxResponse;
use Prophecy\Argument;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setup()
    {
        $clientProphesy = $this->prophesize('\TaxJar\Client');
        $clientProphesy
            ->taxForOrder(
                Argument::type('array')
            )
            ->willReturn(
                json_decode('{"order_total_amount":16.5,"shipping":1.5,"taxable_amount":15,"amount_to_collect":1.31,"rate":0.0875,"has_nexus":true,"freight_taxable":false,"tax_source":"destination","breakdown":{"taxable_amount":15,"tax_collectable":1.31,"combined_tax_rate":0.0875,"state_taxable_amount":15,"state_tax_rate":0.0625,"state_tax_collectable":0.94,"county_taxable_amount":15,"county_tax_rate":0.01,"county_tax_collectable":0.15,"city_taxable_amount":0,"city_tax_rate":0,"city_tax_collectable":0,"special_district_taxable_amount":15,"special_tax_rate":0.015,"special_district_tax_collectable":0.23,"line_items":[{"id":"1","taxable_amount":15,"tax_collectable":1.31,"combined_tax_rate":0.0875,"state_taxable_amount":15,"state_sales_tax_rate":0.0625,"state_amount":0.94,"county_taxable_amount":15,"county_tax_rate":0.01,"county_amount":0.15,"city_taxable_amount":0,"city_tax_rate":0,"city_amount":0,"special_district_taxable_amount":15,"special_tax_rate":0.015,"special_district_amount":0.23}]}}')
            );

        $this->client = new Client($clientProphesy->reveal());
    }

    public function testGetRatesForOrder()
    {
        $order = new Order();
        $order->setFromAddress(new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));
        $order->setToAddress(new Address('1335 E 103rd St', 'Los Angeles', 'CA', '90002', 'US'));
        $order->addLineItem(new LineItem('1', 1, '20010', 15.00, 0.0));
        $order->setAmount(15.0);
        $order->setShipping(1.5);
        $order->addNexusAddress('Main Location', new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));

        $result = $this->client->getTaxesForOrder($order);

        $this->assertInstanceOf(TaxResponse::class, $result);
        $this->assertEquals(16.5, $result->getTotalAmount());
        $this->assertEquals(1.5, $result->getShipping());
        $this->assertEquals(15, $result->getTaxableAmount());
        $this->assertEquals(1.31, $result->getAmountToCollect());
        $this->assertEquals(0.0875, $result->getRate());
        $this->assertEquals(true, $result->hasNexus());
        $this->assertEquals(false, $result->isFreightTaxable());
        $this->assertEquals('destination', $result->getTaxSource());

        $this->assertInstanceOf(TaxBreakdown::class, $result->getTaxBreakdown());
        $this->verifyTaxBreakDown($result->getTaxBreakdown());
    }

    private function verifyTaxBreakDown(TaxBreakdown $taxBreakdown)
    {
        $this->assertEquals(new TaxDetail(15, 0.0875, 1.31), $taxBreakdown->getTotalTax());
        $this->assertEquals(new TaxDetail(15, 0.0625, 0.94), $taxBreakdown->getStateTax());
        $this->assertEquals(new TaxDetail(15, 0.01, 0.15), $taxBreakdown->getCountyTax());
        $this->assertEquals(new TaxDetail(0, 0, 0), $taxBreakdown->getCityTax());
        $this->assertEquals(new TaxDetail(15, 0.015, 0.23), $taxBreakdown->getSpecialDistrictTax());

        $lineItems = $taxBreakdown->getLineItems();
        $this->assertCount(1, $lineItems);
        $lineItem = $lineItems[0];

        $totaxTax = $lineItem->getTotalTax();
        $this->assertEquals(15, $totaxTax->getAmount());
        $this->assertEquals(0.0875, $totaxTax->getTaxRate());
        $this->assertEquals(1.31, $totaxTax->getCollectable());

        $this->assertEquals(new TaxDetail(15, 0.0625, 0.94), $lineItem->getStateTax());
        $this->assertEquals(new TaxDetail(15, 0.01, 0.15), $lineItem->getCountyTax());
        $this->assertEquals(new TaxDetail(0, 0, 0), $lineItem->getCityTax());
        $this->assertEquals(new TaxDetail(15, 0.015, 0.23), $lineItem->getSpecialDistrictTax());
        $this->assertEquals('1', $lineItem->getId());
    }
}
