<?php

namespace TuVan\PricePerCustomer\Ui\Component\Form\Listing\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\Store;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * DataProvider for product listing modal for Price Per Customer Rule Edit
 */
class ProductDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Product collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * @var PoolInterface
     */
    private $modifiersPool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param PoolInterface $modifiersPool
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        PoolInterface $modifiersPool,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->collection->setStoreId(Store::DEFAULT_STORE_ID);
        $this->modifiersPool = $modifiersPool;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection  */
        $productCollection = $this->getCollection();
        if (!$productCollection->isLoaded()) {
            $productCollection->addWebsiteNamesToResult();
            $allowedProductTypes = [
                \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE
            ];
            $productCollection->addAttributeToFilter('type_id', ['in' => $allowedProductTypes]);
            $productCollection->load();
        }
        $items = $productCollection->toArray();

        $data = [
            'totalRecords' => $productCollection->getSize(),
            'items' => array_values($items),
        ];

        foreach ($this->modifiersPool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }

        return $data;
    }
}
