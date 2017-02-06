<?php

namespace LAShowroom\TaxJarBundle\Tests\Model\Transaction;

use LAShowroom\TaxJarBundle\Model\TaxCategory;
use LAShowroom\TaxJarBundle\Model\Transaction\LineItem;

class LineItemTest extends \PHPUnit_Framework_TestCase
{
    public function testDescriptionLength()
    {
        $lineItem = new LineItem('doge', 1, TaxCategory::BOOKS_GENERAL, 1.0);
        $lineItem->setDescription(str_repeat('x', 260));

        $this->assertEquals(255, strlen($lineItem->getDescription()));
        $this->assertStringEndsWith('...', $lineItem->getDescription());
    }
}
