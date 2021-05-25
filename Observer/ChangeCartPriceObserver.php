<?php
namespace TuVan\PricePerCustomer\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session as CustomerModelSession;
use Magento\Framework\Event\ObserverInterface;
use TuVan\PricePerCustomer\Model\Config;
use TuVan\PricePerCustomer\Model\PricePerCustomerFactory;

/**
 * Observer for changing product price after add product to cart
 */
class ChangeCartPriceObserver implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CustomerModelSession
     */
    protected $customerSession;

    /**
     * @var PricePerCustomerFactory
     */
    private $pricePerCustomerFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * ChangeCartPriceObserver constructor.
     *
     * @param CustomerModelSession $customerSession
     * @param PricePerCustomerFactory $pricePerCustomerFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Config $config
     */
    public function __construct(
        CustomerModelSession $customerSession,
        PricePerCustomerFactory $pricePerCustomerFactory,
        ProductRepositoryInterface $productRepository,
        Config $config
    ) {
        $this->customerSession = $customerSession;
        $this->pricePerCustomerFactory = $pricePerCustomerFactory;
        $this->productRepository = $productRepository;
        $this->config = $config;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->isActive() && $customerId = $this->customerSession->getCustomerId()) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $observer->getEvent()->getData('product');
            $requestInfo = $observer->getEvent()->getData('info');
            // Apply Price Per Customer rule when this product doesn't has tier price with total qty adding to cart
            if ($product->getPrice() == $product->getTierPrice($requestInfo['qty'])) {
                // Load child product of configurable which adding to cart
                if ($productId = $requestInfo['selected_configurable_option']) {
                    $product = $this->productRepository->getById($productId);
                } else {
                    $productId = $product->getId();
                }

                if ($product->getPrice() == $product->getTierPrice($requestInfo['qty'])) {
                    /** @var \TuVan\PricePerCustomer\Model\PricePerCustomer $ruleModel */
                    $ruleModel = $this->pricePerCustomerFactory->create();
                    $rulePrice = $ruleModel->getRulePrice($productId, $customerId);
                    if ($rulePrice !== null) {
                        $product->setPrice($rulePrice);
                    }
                }
            }
        }
    }
}
