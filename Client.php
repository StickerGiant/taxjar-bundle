<?php

namespace LAShowroom\TaxJarBundle;

use LAShowroom\TaxJarBundle\Model\Response\TaxResponse;
use LAShowroom\TaxJarBundle\Model\Tax\TaxRequest;
use LAShowroom\TaxJarBundle\Model\Transaction\OrderTransaction;
use TaxJar\Client as TaxJarClient;

class Client
{
    /**
     * @var TaxJarClient
     */
    private $apiClient;

    /**
     * @param TaxJarClient $apiClient
     */
    public function __construct(TaxJarClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @param TaxRequest $order
     *
     * @return TaxResponse
     */
    public function getTaxesForOrder(TaxRequest $order)
    {
        $request = $order->toArray();
        return new TaxResponse($this->apiClient->taxForOrder($request));
    }

    /**
     * @param OrderTransaction $orderTransaction
     *
     * @return TaxResponse
     */
    public function createOrderTransaction(OrderTransaction $orderTransaction)
    {
        return new TaxResponse($this->apiClient->createOrder($orderTransaction->toArray()));
    }
}
