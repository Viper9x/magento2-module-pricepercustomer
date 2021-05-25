<?php

namespace TuVan\PricePerCustomer\Ui\Component\Form;

use Magento\Customer\Api\CustomerRepositoryInterface;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer\Collection;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer\CollectionFactory;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer as PricePerCustomerResource;

/**
 * Data provider for the form of adding new Rule
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var PricePerCustomerResource
     */
    protected $pricePerCustomerResource;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $_session;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResource;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     *  Initialize dependencies.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param PricePerCustomerResource $pricePerCustomerResource
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Magento\Backend\Model\Session $session
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param array $meta
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        PricePerCustomerResource $pricePerCustomerResource,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Backend\Model\Session $session,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->pricePerCustomerResource = $pricePerCustomerResource;
        $this->productResource = $productResource;
        $this->customerRepository = $customerRepository;
        $this->_session = $session;
        $this->_storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get session
     *
     * @return \Magento\Backend\Model\Session
     */
    protected function getSession()
    {
        return $this->_session;
    }

    /**
     * Get data
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \TuVan\PricePerCustomer\Model\PricePerCustomer $rule */
        foreach ($items as $rule) {
            $this->pricePerCustomerResource->load($rule, $rule->getId());
            $rule->setSpecialPrice($rule->getSpecialPrice() * 1);
            $this->loadedData[$rule->getId()] = $rule->getData();
            $customerId = $rule->getCustomerId();
            //Add customer data
            /** @var \Magento\Customer\Model\Data\Customer $customer */
            $customer = $this->customerRepository->getById($customerId);
            $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
            $this->loadedData[$rule->getId()]['customer_name'] = $customerName;
            $this->loadedData[$rule->getId()]['customer_email'] = $customer->getEmail();
            //Add product data
            $productId = $rule->getProductId();
            $store = $this->_storeManager->getStore();
            $productName = $this->productResource->getAttributeRawValue(
                $productId,
                'name',
                $store
            );
            $productSku = $this->productResource->getAttributeRawValue(
                $productId,
                'sku',
                $store
            );
            $productPrice = (float) $this->productResource->getAttributeRawValue(
                $productId,
                'price',
                $store
            );
            // Seems like Magento core return wrong value for SKU attribute
            // It returns an array rather than a string value
            $productSku = (is_array($productSku)) ? $productSku['sku'] : $productSku;
            $this->loadedData[$rule->getId()]['product_name'] = $productName;
            $this->loadedData[$rule->getId()]['product_sku'] = $productSku;
            $this->loadedData[$rule->getId()]['original_price'] = $productPrice;
        }
        $data = $this->getSession()->getRuleFormData();
        if (!empty($data)) {
            $rule = $this->collection->getNewEmptyItem();
            $rule->setData($data);
            $this->loadedData[$rule->getId()] = $rule->getData();
            $this->getSession()->unsRuleFormData();
        }

        return $this->loadedData;
    }
}
