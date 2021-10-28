<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Test\ExportRMA\Model\Export;

use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Framework\Data\Collection;
use Magento\ImportExport\Model\Export\Factory as CollectionFactory;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Test\ExportRMA\Model\Export\Source\Website;

/**
 * @api
 */
class AttributeCollectionProvider
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var AttributeFactory
     */
    private $attributeFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param AttributeFactory $attributeFactory
     * @throws \InvalidArgumentException
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        AttributeFactory  $attributeFactory
    )
    {
        $this->collection = $collectionFactory->create(Collection::class);
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function get(): Collection
    {
        if (count($this->collection) === 0) {
            /** @var \Magento\Eav\Model\Entity\Attribute $websiteIdAttribute */

            $websiteIdAttribute = $this->attributeFactory->create();
//            $websiteIdAttribute->setId(SourceItemInterface::STATUS);
            $websiteIdAttribute->setDefaultFrontendLabel('Website Id');
            $websiteIdAttribute->setAttributeCode('website_id');
            $websiteIdAttribute->setBackendType('int');
            $websiteIdAttribute->setFrontendInput('multiselect');
            $websiteIdAttribute->setSourceModel(Website::class);
            $this->collection->addItem($websiteIdAttribute);
        }

        return $this->collection;
    }
}
