<?php

namespace LAShowroom\TaxJarBundle;

use LAShowroom\TaxJarBundle\Model\Order;
use LAShowroom\TaxJarBundle\Model\Response\TaxResponse;
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
     * @param Order $order
     *
     * @return TaxResponse
     */
    public function getTaxesForOrder(Order $order)
    {
        return new TaxResponse($this->apiClient->taxForOrder($order->toArray()));
    }

}
