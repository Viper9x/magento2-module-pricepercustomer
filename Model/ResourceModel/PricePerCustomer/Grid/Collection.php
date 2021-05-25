<?php

namespace TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer\Grid;

use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

/**
 * Price Per Customer Rule grid collection
 */
class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * Initialize dependencies.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param AttributeRepositoryInterface $attributeRepository
     * @param string $mainTable
     * @param string $resourceModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        AttributeRepositoryInterface $attributeRepository,
        $mainTable = 'price_per_customer_rule',
        $resourceModel = \TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer::class
    ) {
        $this->attributeRepository = $attributeRepository;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    /**
     * Add customer name, product name into grid
     *
     * @return Collection|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $nameAttribute = $this->attributeRepository->get('catalog_product', 'name');
        $priceAttribute = $this->attributeRepository->get('catalog_product', 'price');
        $this->getSelect()
            ->joinLeft(
                ['customer_grid_flat' => $this->getTable('customer_grid_flat')],
                'main_table.customer_id = customer_grid_flat.entity_id',
                ['customer_name' => 'name','customer_email' => 'email']
            )->joinLeft(
                ['catalog_product_entity' => $this->getTable('catalog_product_entity')],
                'main_table.product_id = catalog_product_entity.entity_id',
                ['product_sku' => 'sku']
            )->joinLeft(
                ['catalog_product_entity_varchar' => $this->getTable('catalog_product_entity_varchar')],
                'main_table.product_id = catalog_product_entity_varchar.entity_id',
                ['product_name' => 'value']
            )->joinLeft(
                ['catalog_product_entity_decimal' => $this->getTable('catalog_product_entity_decimal')],
                'main_table.product_id = catalog_product_entity_decimal.entity_id',
                ['original_price' => 'value']
            )
            ->where('catalog_product_entity_varchar.attribute_id = ' . (int) $nameAttribute->getAttributeId())
            ->where('catalog_product_entity_decimal.attribute_id = ' . (int) $priceAttribute->getAttributeId());

        return $this;
    }
}
