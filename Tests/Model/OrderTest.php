<?php

namespace LAShowroom\TaxJarBundle\Tests\Model;

use LAShowroom\TaxJarBundle\Model\Address;
use LAShowroom\TaxJarBundle\Model\LineItem;
use LAShowroom\TaxJarBundle\Model\Order;
use LAShowroom\TaxJarBundle\Model\TaxCategory;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $order = self::getTestOrder();

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
        ], $order->toArray());
    }

    public function testSetNexusAddresses()
    {
        $order = new Order();

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
        $order = new Order();
        $order->setFromAddress($address1 = new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));
        $order->setToAddress($address2 = new Address('1335 E 103rd St', 'Los Angeles', 'CA', '90002', 'US'));

        $this->assertEquals($address1, $order->getFromAddress());
        $this->assertEquals($address2, $order->getToAddress());
    }

    public function testSetLineItems()
    {
        $order = new Order();
        $order->setLineItems([
            $firstLineItem = new LineItem('1', 1, TaxCategory::CLOTHING, 15.00, 0.0),
            $secondLineItem = new LineItem('2', 1, TaxCategory::CLOTHING, 16.00, 0.0)
        ]);

        $this->assertCount(2, $order->getLineItems());
        $this->assertEquals([$firstLineItem, $secondLineItem], $order->getLineItems());
    }

    public function testCacheKeySameForSameData()
    {
        $this->assertEquals('order_c3e3b1ddda8a59ac5b4158c64461536f', self::getTestOrder()->getCacheKey());
        $this->assertEquals('order_c3e3b1ddda8a59ac5b4158c64461536f', self::getTestOrder()->getCacheKey());
    }

    public function testCacheKeyChangesAsDataChanged()
    {
        $order = self::getTestOrder();
        $this->assertEquals('order_c3e3b1ddda8a59ac5b4158c64461536f', $order->getCacheKey());

        $order->setAmount(20.00);

        $this->assertEquals('order_0b0aa4abd232d4d9b88e6e710cb2ae9b', $order->getCacheKey());
    }

    public function testTransactionData()
    {
        $order = new Order();
        $order->setTransactionId('doge');
        $this->assertEquals('doge', $order->getTransactionId());
        $date = new \DateTime();
        $order->setTransactionDate($date);
        $this->assertEquals($date, $order->getTransactionDate());
        $order->setSalesTax(1.23);
        $this->assertEquals(1.23, $order->getSalesTax());


        $this->assertEquals('doge', $order->toArray()['transaction_id']);
        $this->assertEquals($date->format(\DATE_ISO8601), $order->toArray()['transaction_date']);
        $this->assertEquals(1.23, $order->toArray()['sales_tax']);
    }

    /**
     * @return Order
     */
    public static function getTestOrder()
    {
        $order = new Order();
        $order->setFromAddress(new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));
        $order->setToAddress(new Address('1335 E 103rd St', 'Los Angeles', 'CA', '90002', 'US'));
        $order->addLineItem(new LineItem('1', 1, TaxCategory::CLOTHING, 15.00, 0.0));
        $order->setAmount(15.0);
        $order->setShipping(1.5);
        $order->addNexusAddress('Main Location', new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));

        return $order;
    }
}
