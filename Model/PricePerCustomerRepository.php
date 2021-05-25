<?php

namespace TuVan\PricePerCustomer\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer as PricePerCustomerResource;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer\CollectionFactory;

/**
 * Price Per Customer Rule CURD Class
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PricePerCustomerRepository implements PricePerCustomerRepositoryInterface
{
    /**
     * @var \TuVan\PricePerCustomer\Api\Data\PricePerCustomerSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var PricePerCustomerResource
     */
    private $pricePerCustomerResource;

    /**
     * @var PricePerCustomerFactory
     */
    protected $pricePerCustomerFactory;

    /**
     * @param PricePerCustomerFactory $pricePerCustomerFactory
     * @param PricePerCustomerResource $pricePerCustomerResource
     * @param CollectionFactory $collectionFactory
     * @param \TuVan\PricePerCustomer\Api\Data\PricePerCustomerSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        PricePerCustomerFactory $pricePerCustomerFactory,
        PricePerCustomerResource $pricePerCustomerResource,
        CollectionFactory $collectionFactory,
        \TuVan\PricePerCustomer\Api\Data\PricePerCustomerSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->pricePerCustomerFactory =  $pricePerCustomerFactory;
        $this->pricePerCustomerResource =  $pricePerCustomerResource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save Rule data
     *
     * @param \TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface $rule
     * @return \TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface
     * @throws CouldNotSaveException
     */
    public function save(\TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface $rule)
    {
        try {
            $this->pricePerCustomerResource->save($rule);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $rule;
    }

    /**
     * Load Rule data by given rule Identity
     *
     * @param string $id
     * @return PricePerCustomer
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $rule = $this->pricePerCustomerFactory->create();
        $this->pricePerCustomerResource->load($rule, $id);
        if (!$rule->getId()) {
            throw new NoSuchEntityException(__('The rule with the "%1" ID doesn\'t exist.', $id));
        }
        return $rule;
    }

    /**
     * Load Rules data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \TuVan\PricePerCustomer\Api\Data\PricePerCustomerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var CollectionFactory $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var \TuVan\PricePerCustomer\Api\Data\PricePerCustomerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete Rule
     *
     * @param \TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface $rule
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(\TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface $rule)
    {
        try {
            $this->pricePerCustomerResource->delete($rule);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the rule: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * Delete Rule by ID
     *
     * @param string $id
     * @return bool true on success
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        $ruleModel = $this->getById($id);
        return $this->delete($ruleModel);
    }
}
