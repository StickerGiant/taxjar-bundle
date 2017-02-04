<?php

namespace LAShowroom\TaxJarBundle\Tests;

use LAShowroom\TaxJarBundle\Client;
use LAShowroom\TaxJarBundle\Model\Address;
use LAShowroom\TaxJarBundle\Model\LineItem;
use LAShowroom\TaxJarBundle\Model\Order;
use LAShowroom\TaxJarBundle\Model\Response\TaxBreakdown;
use LAShowroom\TaxJarBundle\Model\Response\TaxDetail;
use LAShowroom\TaxJarBundle\Model\Response\TaxResponse;
use LAShowroom\TaxJarBundle\Tests\Model\OrderTest;
use LAShowroom\TaxJarBundle\Tests\Model\Tax\TaxRequestTest;
use LAShowroom\TaxJarBundle\Tests\Model\Transaction\OrderTransactionTest;
use Prophecy\Argument;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\CacheItem;
use TaxJar\TaxJar;

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
                json_decode('{"order_total_amount":15.0,"shipping":1.5,"taxable_amount":15,"amount_to_collect":1.31,"rate":0.0875,"has_nexus":true,"freight_taxable":false,"tax_source":"destination","breakdown":{"taxable_amount":15,"tax_collectable":1.31,"combined_tax_rate":0.0875,"state_taxable_amount":15,"state_tax_rate":0.0625,"state_tax_collectable":0.94,"county_taxable_amount":15,"county_tax_rate":0.01,"county_tax_collectable":0.15,"city_taxable_amount":0,"city_tax_rate":0,"city_tax_collectable":0,"special_district_taxable_amount":15,"special_tax_rate":0.015,"special_district_tax_collectable":0.23,"line_items":[{"id":"1","taxable_amount":15,"tax_collectable":1.31,"combined_tax_rate":0.0875,"state_taxable_amount":15,"state_sales_tax_rate":0.0625,"state_amount":0.94,"county_taxable_amount":15,"county_tax_rate":0.01,"county_amount":0.15,"city_taxable_amount":0,"city_tax_rate":0,"city_amount":0,"special_district_taxable_amount":15,"special_tax_rate":0.015,"special_district_amount":0.23}]}}')
            )
        ;

        $clientProphesy
            ->createOrder(
                Argument::type('array')
            )
            ->willReturn(
                json_decode('{"transaction_id":"1234567","user_id":36803,"transaction_date":"2017-02-02T18:57:32.000Z","transaction_reference_id":null,"from_country":"US","from_zip":"92093","from_state":"CA","from_city":"LA JOLLA","from_street":"9500 Gilman Drive","to_country":"US","to_zip":"90002","to_state":"CA","to_city":"LOS ANGELES","to_street":"1335 E 103rd St","amount":"16.5","shipping":"1.5","sales_tax":"1.23","line_items":[{"id":0,"quantity":1,"product_identifier":null,"product_tax_code":"20010","description":null,"unit_price":"15.0","discount":"0.0","sales_tax":"0.0"}]}')
            )
        ;

        $this->client = new Client($clientProphesy->reveal());
    }

    public function testGetRatesForOrder()
    {
        $order = TaxRequestTest::getTestRequest();

        $result = $this->client->getTaxesForOrder($order);

        $this->assertInstanceOf(TaxResponse::class, $result);
        $this->assertEquals(15.0, $result->getTotalAmount());
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

    public function testCacheCold()
    {
        $cache = new ArrayAdapter();
        $this->assertFalse($cache->hasItem(TaxRequestTest::getTestRequest()->getCacheKey()));

        $this->client->setCacheItemPool($cache);

        $result = $this->client->getTaxesForOrder(TaxRequestTest::getTestRequest());
        $this->assertInstanceOf(TaxResponse::class, $result);

        $this->assertTrue($cache->hasItem(TaxRequestTest::getTestRequest()->getCacheKey()));
        $this->assertEquals($result, $cache->getItem(TaxRequestTest::getTestRequest()->getCacheKey())->get());
    }

    public function testCacheWarm()
    {
        $cache = new ArrayAdapter();
        $this->assertFalse($cache->hasItem(TaxRequestTest::getTestRequest()->getCacheKey()));
        $cacheItem = $cache->getItem(TaxRequestTest::getTestRequest()->getCacheKey());

        $result = $this->client->getTaxesForOrder(TaxRequestTest::getTestRequest());

        $cacheItem->set($result);
        $cache->save($cacheItem);

        $clientProphesy = $this->prophesize('\TaxJar\Client');
        $clientProphesy
            ->taxForOrder(
                Argument::type('array')
            )->shouldNotBeCalled();

        $client = new Client($clientProphesy->reveal());
        $client->setCacheItemPool($cache);

        $resultFromCache = $client->getTaxesForOrder(TaxRequestTest::getTestRequest());
        $this->assertInstanceOf(TaxResponse::class, $resultFromCache);

        $this->assertEquals($result, $resultFromCache);
    }

    public function testCreateTransaction()
    {
        $order = OrderTransactionTest::getTestOrderTransaction();
        $order->setTransactionId('1234567');
        $order->setTransactionDate($now = new \DateTime);
        $order->setSalesTax(1.23);

        $result = $this->client->createOrderTransaction($order);

        $this->assertInstanceOf(TaxResponse::class, $result);
        $this->assertEquals(16.5, $result->getTotalAmount());
        $this->assertEquals(1.5, $result->getShipping());
        $this->assertEquals(1.23, $result->getSalesTax());
        $this->assertEquals(36803, $result->getUserId());
        $this->assertEquals(new \DateTime('2017-02-02T18:57:32.000000+0000'), $result->getTransactionDate());
        $this->assertNull($result->getTransactionReferenceId());
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
