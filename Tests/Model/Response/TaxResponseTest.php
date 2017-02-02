<?php

namespace LAShowroom\TaxJarBundle\Tests\Model\Response;

use LAShowroom\TaxJarBundle\Model\Response\TaxResponse;

class TaxResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testTotalAmount()
    {
        $response = new \stdClass();
        $response->order_total_amount = 1.23;

        $taxResponse = new TaxResponse($response);
        $this->assertEquals(1.23, $taxResponse->getTotalAmount());

        $response = new \stdClass();
        $response->amount = 1.23;

        $taxResponse = new TaxResponse($response);
        $this->assertEquals(1.23, $taxResponse->getTotalAmount());
    }

    public function testFreighTaxable()
    {
        $response = new \stdClass();
        $response->freight_taxable = 1;

        $taxResponse = new TaxResponse($response);
        $this->assertTrue($taxResponse->isFreightTaxable());
    }

    public function testTransactionReferenceId()
    {
        $response = new \stdClass();
        $response->transaction_reference_id = 'doge';

        $taxResponse = new TaxResponse($response);
        $this->assertEquals('doge', $taxResponse->getTransactionReferenceId());
    }
}
