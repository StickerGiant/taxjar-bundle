<?php

namespace LAShowroom\TaxJarBundle\Model\Tax;

use LAShowroom\TaxJarBundle\Model\Address;
use LAShowroom\TaxJarBundle\Model\BaseRequest;
use LAShowroom\TaxJarBundle\Model\LineItem;
use Webmozart\Assert\Assert;

class TaxRequest extends BaseRequest
{
    /**
     * @var Address[]
     */
    private $nexusAddresses = [];

    /**
     * @var LineItem[]
     */
    private $lineItems = [];

    /**
     * @return Address[]
     */
    public function getNexusAddresses()
    {
        return $this->nexusAddresses;
    }

    /**
     * @param Address[] $nexusAddresses
     */
    public function setNexusAddresses(array $nexusAddresses)
    {
        Assert::allIsInstanceOf($nexusAddresses, Address::class);

        $this->nexusAddresses = $nexusAddresses;
    }

    public function addNexusAddress($id, Address $address)
    {
        $this->nexusAddresses[$id] = $address;
    }

    /**
     * @return LineItem[]
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * @param LineItem[] $lineItems
     */
    public function setLineItems(array $lineItems)
    {
        Assert::allIsInstanceOf($lineItems, LineItem::class);

        $this->lineItems = $lineItems;
    }

    public function addLineItem(LineItem $lineItem)
    {
        $this->lineItems[] = $lineItem;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(),
        [
            'line_items' => array_map(function(LineItem $elem) {
                return $elem->toArray();
            }, $this->lineItems),
            'nexus_addresses' => array_map(function(Address $elem, $id) {
                return array_merge($elem->toArray(), ['id' => $id, ]);
            }, $this->nexusAddresses, array_keys($this->nexusAddresses)),
        ]);
    }

    public function getCacheKey()
    {
        return sprintf('tax_request_%s', md5(json_encode($this->toArray())));
    }
}
