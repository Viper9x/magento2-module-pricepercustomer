<?php

namespace TuVan\PricePerCustomer\Pricing\Render;

use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;
use TuVan\PricePerCustomer\Model\Config;
use TuVan\PricePerCustomer\Model\PricePerCustomerFactory;

/**
 * Class for final_price box rendering
 */
class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
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
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param SalableResolverInterface $salableResolver
     * @param MinimalPriceCalculatorInterface $minimalPriceCalculator
     * @param PricePerCustomerFactory $pricePerCustomerFactory
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        SalableResolverInterface $salableResolver,
        MinimalPriceCalculatorInterface $minimalPriceCalculator,
        PricePerCustomerFactory $pricePerCustomerFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        Config $config,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $saleableItem,
            $price,
            $rendererPool,
            $data,
            $salableResolver,
            $minimalPriceCalculator
        );

        $this->pricePerCustomerFactory = $pricePerCustomerFactory;
        $this->httpContext = $httpContext;
        $this->config = $config;
    }

    /**
     * Check is Price Per Customer Rule applicable for the current product.
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isPricePerCustomerRuleApplicable()
    {
        if ($this->config->isActive() && $customerId = $this->httpContext->getValue('customer_id')) {
            /** @var \TuVan\PricePerCustomer\Model\PricePerCustomer $ruleModel */
            $ruleModel = $this->pricePerCustomerFactory->create();
            $rulePrice = $ruleModel->getRulePrice($this->getSaleableItem()->getId(), $customerId);

            if ($rulePrice !== null) {
                return true;
            }
        }
        return false;
    }
}
