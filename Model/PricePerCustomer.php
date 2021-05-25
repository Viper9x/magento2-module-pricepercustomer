<?php

namespace TuVan\PricePerCustomer\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface;
use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;

/**
 * PricePerCustomer Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PricePerCustomer extends AbstractModel implements PricePerCustomerInterface
{
    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @var PricePerCustomerRepositoryInterface
     */
    private $pricePerCustomerRepository;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterGroupBuilder
     */
    private $filterGroupBuilder;

    /**
     * PricePerCustomer constructor.
     * @param TimezoneInterface $timezone
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PricePerCustomerRepositoryInterface $pricePerCustomerRepository
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        TimezoneInterface $timezone,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PricePerCustomerRepositoryInterface $pricePerCustomerRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->timezone = $timezone;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->pricePerCustomerRepository = $pricePerCustomerRepository;

        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Set resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel\PricePerCustomer::class);
    }

    /**
     * Get customer id
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->_getData('customer_id');
    }

    /**
     * Set customer id
     *
     * @param string $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData('customer_id', $customerId);
    }

    /**
     * Get product id
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->_getData('product_id');
    }

    /**
     * Set product id
     *
     * @param string $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        return $this->setData('product_id', $productId);
    }

    /**
     * Get specialPrice
     *
     * @return float
     */
    public function getSpecialPrice()
    {
        return $this->_getData('special_price');
    }

    /**
     * Set specialPrice
     *
     * @param float $price
     * @return $this
     */
    public function setSpecialPrice($price)
    {
        return $this->setData('special_price', $price);
    }

    /**
     * Get the start date when the rule is active
     *
     * @return string
     */
    public function getFromDate()
    {
        return $this->_getData('from_date');
    }

    /**
     * Set the start date when the rule is active
     *
     * @param string $fromDate
     * @return $this
     */
    public function setFromDate($fromDate)
    {
        return $this->setData('from_date', $fromDate);
    }

    /**
     * Get the end date when the rule is active
     *
     * @return string
     */
    public function getToDate()
    {
        return $this->_getData('from_date');
    }

    /**
     * Set the end date when the rule is active
     *
     * @param string $toDate
     * @return $this
     */
    public function setToDate($toDate)
    {
        return $this->setData('to_date', $toDate);
    }

    /**
     * Validate rule data
     *
     * @param \Magento\Framework\DataObject $dataObject
     * @return bool|string[] - return true if validation passed successfully. Array with errors description otherwise
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @throws \Exception
     */
    public function validateData(\Magento\Framework\DataObject $dataObject)
    {
        $result = [];
        $fromDate = $toDate = null;

        if ($dataObject->hasFromDate() && $dataObject->hasToDate()) {
            $fromDate = $dataObject->getFromDate();
            $toDate = $dataObject->getToDate();
        }

        if ($fromDate && $toDate) {
            $fromDate = new \DateTime($fromDate);
            $toDate = new \DateTime($toDate);

            if ($fromDate > $toDate) {
                $result[] = __('End Date must follow Start Date.');
            }
        }

        return !empty($result) ? $result : true;
    }

    /**
     * Get Rule price
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRulePrice($productId, $customerId)
    {
        // Get Price Per Customer Rule match condition
        $isActiveFilter = $this->filterBuilder->setField('is_active')
            ->setValue(1)
            ->create();
        $customerFilter =$this->filterBuilder->setField('customer_id')
            ->setValue($customerId)
            ->create();
        $productFilter = $this->filterBuilder->setField('product_id')
            ->setValue($productId)
            ->create();
        $fromDateFilterGt = $this->filterBuilder->setField('from_date')
            ->setConditionType('lteq')
            ->setValue(
                $this->timezone->date()->format('Y-m-d')
            )
            ->create();
        $fromDateFilterNull = $this->filterBuilder->setField('from_date')
            ->setConditionType('null')
            ->create();
        $toDateFilterGt = $this->filterBuilder->setField('to_date')
            ->setConditionType('gteq')
            ->setValue(
                $this->timezone->date()->format('Y-m-d')
            )
            ->create();
        $toDateFilterNull = $this->filterBuilder->setField('to_date')
            ->setConditionType('null')
            ->create();
        $filterGroups = [];
        // AND expression
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$isActiveFilter])->create();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$customerFilter])->create();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$productFilter])->create();
        // OR expression
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$fromDateFilterNull, $fromDateFilterGt])->create();
        $filterGroups[] = $this->filterGroupBuilder->setFilters([$toDateFilterNull, $toDateFilterGt])->create();
        $this->searchCriteriaBuilder->setFilterGroups($filterGroups);

        $ruleItems = $this->pricePerCustomerRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        $rulePrice = null;
        if (count($ruleItems)) {
            $rule = current($ruleItems);
            $rulePrice = (float) $rule->getSpecialPrice();
        }

        return $rulePrice;
    }
}
