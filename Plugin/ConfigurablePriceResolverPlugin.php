<?php
namespace TuVan\PricePerCustomer\Plugin;

use Magento\ConfigurableProduct\Pricing\Price\ConfigurablePriceResolver;
use Magento\Framework\Pricing\SaleableInterface;
use TuVan\PricePerCustomer\Model\Config;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer\CollectionFactory;

class ConfigurablePriceResolverPlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;

    /**
     * ChangeProductPrice constructor.
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Config $config
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        Config $config
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->httpContext = $httpContext;
        $this->config = $config;
    }

    public function afterResolvePrice(ConfigurablePriceResolver $subject, $result, SaleableInterface $product)
    {
        if ($this->config->isActive() && $customerId = $this->httpContext->getValue('customer_id')) {
            /** @var \Magento\Catalog\Model\Product $product */
            $childProducts = $product->getTypeInstance()->getUsedProducts($product);
            $childProductIds = [];
            foreach ($childProducts as $childProduct) {
                $childProductIds[] = $childProduct->getId();
            }
            // Get min Price Per Customer of child products
            /** @var \TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer\Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->getSelect()
                ->reset(
                    \Magento\Framework\DB\Select::COLUMNS
                )->columns(
                    ['product_id', 'special_price']
                )->where('`product_id` IN (?)', $childProductIds)
                ->where('customer_id = ?', $customerId)
                ->order('special_price ASC')->limit(1);
            $items = $collection->getItems();
            if (count($items)) {
                $rule = current($items);
                $rulePrice = (float) $rule->getSpecialPrice();

                // Compare min rule price with default min price
                if ($rulePrice < $result) {
                    return $rulePrice;
                }
            }
        }

        return $result;
    }
}
