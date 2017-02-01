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
        if (null === $this->cacheItemPool) {
            return new TaxResponse($this->apiClient->taxForOrder($order->toArray()));
        }

        /** @var \Symfony\Component\Cache\CacheItem $cacheItem */
        $cacheItem = $this->cacheItemPool->getItem($order->getCacheKey());

        if (!$cacheItem->isHit()) {
            $cacheItem->set(new TaxResponse($this->apiClient->taxForOrder($order->toArray())));
            $this->cacheItemPool->save($cacheItem);
            $cacheItem->expiresAt(new \DateTime('+1 day'));
        }

        return $cacheItem->get();
    }

    /**
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function setCacheItemPool(CacheItemPoolInterface $cacheItemPool)
    {
        $this->cacheItemPool = $cacheItemPool;
    }
}
