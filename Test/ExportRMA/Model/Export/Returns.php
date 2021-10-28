<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Test\ExportRMA\Model\Export;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\ImportExport\Model\Export\Factory as ExportFactory;
use Magento\ImportExport\Model\Export\AbstractEntity;
use Magento\Inventory\Model\ResourceModel\SourceItem;
use Magento\Inventory\Model\ResourceModel\SourceItem\Collection as SourceItemCollection;
use Test\ExportRMA\Model\Export\SourceItemCollectionFactoryInterface;
use Test\ExportRMA\Model\Model\Export\ColumnProviderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\ImportExport\Model\ResourceModel\CollectionByPagesIteratorFactory;

/**
 * Class Returns
 */
class Returns extends AbstractEntity
{
    /**
     * @var AttributeCollectionProvider
     */
    private $attributeCollectionProvider;

    /**
     * @var SourceItemCollectionFactoryInterface
     */
    private $sourceItemCollectionFactory;

    /**
     * @var ColumnProviderInterface
     */
    private $columnProvider;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param ExportFactory $collectionFactory
     * @param CollectionByPagesIteratorFactory $resourceColFactory
     * @param AttributeCollectionProvider $attributeCollectionProvider
     * @param SourceItemCollectionFactoryInterface $sourceItemCollectionFactory
     * @param ColumnProviderInterface $columnProvider
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ExportFactory $collectionFactory,
        CollectionByPagesIteratorFactory $resourceColFactory,
        AttributeCollectionProvider $attributeCollectionProvider,
        SourceItemCollectionFactoryInterface $sourceItemCollectionFactory,
        ColumnProviderInterface $columnProvider,
        array $data = []
    ) {
        $this->attributeCollectionProvider = $attributeCollectionProvider;
        $this->sourceItemCollectionFactory = $sourceItemCollectionFactory;
        $this->columnProvider = $columnProvider;
        parent::__construct($scopeConfig, $storeManager, $collectionFactory, $resourceColFactory, $data);
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function getAttributeCollection()
    {
        return $this->attributeCollectionProvider->get();
    }

    public function export()
    {
        $writer = $this->getWriter();
        $writer->setHeaderCols($this->_getHeaderColumns());

        /** @var SourceItemCollection $collection */
        $collection = $this->sourceItemCollectionFactory->create(
            $this->getAttributeCollection(),
            $this->_parameters
        );

        foreach ($collection->getData() as $data) {
            unset($data[SourceItem::ID_FIELD_NAME]);
            $writer->writeRow($data);
        }

        return $writer->getContents();
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    protected function _getHeaderColumns()
    {
        return $this->columnProvider->getHeaders($this->getAttributeCollection(), $this->_parameters);
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function exportItem($item)
    {
        // will not implement this method as it is legacy interface
    }

    /**
     * @inheritdoc
     */
    public function getEntityTypeCode()
    {
        return 'returns';
    }

    public function filterAttributeCollection(\Magento\Framework\Data\Collection $collection)
    {
        /** @var $attribute \Magento\Customer\Model\Attribute */
        foreach (parent::filterAttributeCollection($collection) as $attribute) {
            if (!empty($this->_attributeOverrides[$attribute->getAttributeCode()])) {
                $data = $this->_attributeOverrides[$attribute->getAttributeCode()];

                if (isset($data['options_method']) && method_exists($this, $data['options_method'])) {
                    $data['filter_options'] = $this->{$data['options_method']}();
                }
                $attribute->addData($data);
            }
        }
        return $collection;
    }

    /**
     * @inheritdoc
     */
    protected function _getEntityCollection()
    {
        // will not implement this method as it is legacy interface
    }
}
