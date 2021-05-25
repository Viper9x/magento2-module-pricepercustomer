<?php

namespace TuVan\PricePerCustomer\Plugin;

use TuVan\PricePerCustomer\Model\PricePerCustomerFactory;
use TuVan\PricePerCustomer\Model\Config;

/**
 * Observer for changing product price for frontend area
 */
class FinalPricePlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var PricePerCustomerFactory
     */
    private $pricePerCustomerFactory;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;

    /**
     * ChangeProductPrice constructor.
     * @param PricePerCustomerFactory $pricePerCustomerFactory
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param Config $config
     */
    public function __construct(
        PricePerCustomerFactory $pricePerCustomerFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        Config $config
    )
    {
        $this->pricePerCustomerFactory = $pricePerCustomerFactory;
        $this->httpContext = $httpContext;
        $this->config = $config;
    }

    public function afterGetValue(\Magento\Catalog\Pricing\Price\FinalPrice $subject, $result)
    {
        if ($this->config->isActive() && $customerId = $this->httpContext->getValue('customer_id')) {
            /* @var \TuVan\PricePerCustomer\Model\PricePerCustomer $ruleModel */
            $ruleModel = $this->pricePerCustomerFactory->create();
            $rulePrice = $ruleModel->getRulePrice($subject->getProduct()->getId(), $customerId);
            //Process change price
            if ($rulePrice !== null) {
                $result = $rulePrice;
            }
        }
        return $result;
    }
}
