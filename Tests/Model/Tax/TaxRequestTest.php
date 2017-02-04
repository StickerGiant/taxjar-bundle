<?php

namespace LAShowroom\TaxJarBundle\Tests\Model\Tax;

use LAShowroom\TaxJarBundle\Model\Address;
use LAShowroom\TaxJarBundle\Model\LineItem;
use LAShowroom\TaxJarBundle\Model\Order;
use LAShowroom\TaxJarBundle\Model\Tax\TaxRequest;
use LAShowroom\TaxJarBundle\Model\TaxCategory;

class TaxRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $request = self::getTestRequest();

        $this->assertEquals([
            'from_country' => 'US',
            'from_zip' => '92093',
            'from_state' => 'CA',
            'from_city' => 'La Jolla',
            'from_street' => '9500 Gilman Drive',
            'to_country' => 'US',
            'to_zip' => '90002',
            'to_state' => 'CA',
            'to_city' => 'Los Angeles',
            'to_street' => '1335 E 103rd St',
            'amount' => 15,
            'shipping' => 1.5,
            'line_items' => [
                0 => [
                    'id' => '1',
                    'quantity' => 1,
                    'product_tax_code' => '20010',
                    'unit_price' => 15,
                    'discount' => 0,
                ]
            ],
            'nexus_addresses' => [
                [
                    'id' => 'Main Location',
                    'country' => 'US',
                    'zip' => '92093',
                    'state' => 'CA',
                    'city' => 'La Jolla',
                    'street' => '9500 Gilman Drive',
                ]
            ],
        ], $request->toArray());
    }

    public function testSetNexusAddresses()
    {
        $order = new TaxRequest();

        $order->setNexusAddresses([
            'Main Location' => new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'),
            'doge' => new Address('1335 E 103rd St', 'Los Angeles', 'CA', '90002', 'US'),
        ]);

        $this->assertCount(2, $order->getNexusAddresses());
        $this->assertArrayHasKey('Main Location', $order->getNexusAddresses());
        $this->assertArrayHasKey('doge', $order->getNexusAddresses());
    }

    public function testSetAddresses()
    {
        $order = new TaxRequest();
        $order->setFromAddress($address1 = new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));
        $order->setToAddress($address2 = new Address('1335 E 103rd St', 'Los Angeles', 'CA', '90002', 'US'));

        $this->assertEquals($address1, $order->getFromAddress());
        $this->assertEquals($address2, $order->getToAddress());
    }

    public function testSetLineItems()
    {
        $order = new TaxRequest();
        $order->setLineItems([
            $firstLineItem = new LineItem('1', 1, TaxCategory::CLOTHING, 15.00, 0.0),
            $secondLineItem = new LineItem('2', 1, TaxCategory::CLOTHING, 16.00, 0.0)
        ]);

        $this->assertCount(2, $order->getLineItems());
        $this->assertEquals([$firstLineItem, $secondLineItem], $order->getLineItems());
    }

    public function testCacheKeySameForSameData()
    {
        $this->assertEquals('tax_request_6e176a6f7410495036bb4980fdef538d', self::getTestRequest()->getCacheKey());
        $this->assertEquals('tax_request_6e176a6f7410495036bb4980fdef538d', self::getTestRequest()->getCacheKey());
    }

    public function testCacheKeyChangesAsDataChanged()
    {
        $order = self::getTestRequest();
        $this->assertEquals('tax_request_6e176a6f7410495036bb4980fdef538d', $order->getCacheKey());

        $order->setAmount(20.00);

        $this->assertEquals('tax_request_5c42a8050be9a9aec8a42c9e807939ac', $order->getCacheKey());
    }
//


    /**
     * @return TaxRequest
     */
    public static function getTestRequest()
    {
        $order = new TaxRequest();
        $order->setFromAddress(new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));
        $order->setToAddress(new Address('1335 E 103rd St', 'Los Angeles', 'CA', '90002', 'US'));
        $order->addLineItem(new LineItem('1', 1, TaxCategory::CLOTHING, 15.00, 0.0));
        $order->setAmount(15.0);
        $order->setShipping(1.5);
        $order->addNexusAddress('Main Location', new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));

        return $order;
    }
}
