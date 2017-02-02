<?php

namespace LAShowroom\TaxJarBundle;

use LAShowroom\TaxJarBundle\Model\Order;
use LAShowroom\TaxJarBundle\Model\Response\TaxResponse;
use Psr\Cache\CacheItemPoolInterface;
use TaxJar\Client as TaxJarClient;

class Client
{
    /**
     * @var TaxJarClient
     */
    private $apiClient;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

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
        $request = $order->toArray();
        unset($request['transaction_id']);
        unset($request['transaction_date']);

        if (null === $this->cacheItemPool) {
            return new TaxResponse($this->apiClient->taxForOrder($request));
        }

        /** @var \Symfony\Component\Cache\CacheItem $cacheItem */
        $cacheItem = $this->cacheItemPool->getItem($order->getCacheKey());

        if (!$cacheItem->isHit()) {
            $cacheItem->set(new TaxResponse($this->apiClient->taxForOrder($request)));
            $this->cacheItemPool->save($cacheItem);
            $cacheItem->expiresAt(new \DateTime('+1 day'));
        }

        return $cacheItem->get();
    }

    /**
     * @param Order $order
     *
     * @return TaxResponse
     */
    public function createOrderTransaction(Order $order)
    {
        $request = $order->toArray();

        $response = new TaxResponse($this->apiClient->createOrder($request));

        return $response;
    }

    /**
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function setCacheItemPool(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }
}
