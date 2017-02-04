<?php

namespace LAShowroom\TaxJarBundle\Tests\Model\Transaction;

use LAShowroom\TaxJarBundle\Model\Address;
use LAShowroom\TaxJarBundle\Model\TaxCategory;
use LAShowroom\TaxJarBundle\Model\Transaction\LineItem;
use LAShowroom\TaxJarBundle\Model\Transaction\OrderTransaction;

class OrderTransactionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return OrderTransaction
     */
    public static function getTestOrderTransaction()
    {
        $order = new OrderTransaction();
        $order->setFromAddress(new Address('9500 Gilman Drive', 'La Jolla', 'CA', '92093', 'US'));
        $order->setToAddress(new Address('1335 E 103rd St', 'Los Angeles', 'CA', '90002', 'US'));
        $order->addLineItem(new LineItem('1', 1, TaxCategory::CLOTHING, 15.00, 0.0));
        $order->setAmount(15.0);
        $order->setShipping(1.5);

        return $order;
    }

    public function testTransactionData()
    {
        $order = new OrderTransaction();
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

    public function testAddLineItem()
    {
        $firstLineItem = new LineItem('1', 1, TaxCategory::CLOTHING, 15.00, 0.0);
        $firstLineItem->setDescription($expectedDescription = 'description_1');
        $firstLineItem->setSalesTax($expectedSalesTax = 1.50);
        $firstLineItem->setProductIdentifier($expectedProductIdentifier = 'identifier_1');

        $secondLineItem = new LineItem('2', 1, TaxCategory::CLOTHING, 16.00, 0.0);
        $secondLineItem->setDescription('description_2');
        $secondLineItem->setSalesTax(1.60);
        $secondLineItem->setProductIdentifier('identifier_2');
        $order = new OrderTransaction();
        $order->addLineItem($firstLineItem);

        $this->assertCount(1, $order->getLineItems());
        $this->assertContainsOnlyInstancesOf(LineItem::class, $order->getLineItems());
        $this->assertEquals($expectedDescription, $order->getLineItems()[0]->getDescription());
        $this->assertEquals($expectedSalesTax, $order->getLineItems()[0]->getSalesTax());
        $this->assertEquals($expectedProductIdentifier, $order->getLineItems()[0]->getProductIdentifier());

        $order->addLineItem($firstLineItem);
        $this->assertCount(2, $order->getLineItems());
        $this->assertContainsOnlyInstancesOf(LineItem::class, $order->getLineItems());
    }

    public function testSetLineItem()
    {
        $firstLineItem = new LineItem('1', 1, TaxCategory::CLOTHING, 15.00, 0.0);
        $firstLineItem->setDescription('description_1');
        $firstLineItem->setSalesTax(1.50);
        $firstLineItem->setProductIdentifier('identifier_1');

        $secondLineItem = new LineItem('2', 1, TaxCategory::CLOTHING, 16.00, 0.0);
        $secondLineItem->setDescription('description_2');
        $secondLineItem->setSalesTax(1.60);
        $secondLineItem->setProductIdentifier('identifier_2');

        $order = new OrderTransaction();
        $order->setLineItems($expected = [$firstLineItem, $secondLineItem]);

        $this->assertCount(2, $order->getLineItems());
        $this->assertContainsOnlyInstancesOf(LineItem::class, $order->getLineItems());
        $this->assertEquals($expected, $order->getLineItems());
    }
}
