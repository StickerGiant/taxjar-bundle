<?php

namespace LAShowroom\TaxJarBundle;

use LAShowroom\TaxJarBundle\Model\Response\TaxResponse;
use LAShowroom\TaxJarBundle\Model\Tax\TaxRequest;
use LAShowroom\TaxJarBundle\Model\Transaction\OrderTransaction;
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
     * @param TaxRequest $order
     *
     * @return TaxResponse
     */
    public function getTaxesForOrder(TaxRequest $order)
    {
        $request = $order->toArray();

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
     * @param OrderTransaction $orderTransaction
     *
     * @return TaxResponse
     */
    public function createOrderTransaction(OrderTransaction $orderTransaction)
    {
        return new TaxResponse($this->apiClient->createOrder($orderTransaction->toArray()));
    }

    /**
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function setCacheItemPool(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }
}
