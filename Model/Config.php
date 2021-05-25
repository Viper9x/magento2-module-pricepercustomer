<?php

namespace TuVan\PricePerCustomer\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * PricePerCustomer configuration model
 */
class Config
{
    /**
     * Configuration path to Price Per Customer rule active setting
     */
    private const XML_PATH_PRICE_PER_CUSTOMER_ACTIVE = 'price_per_customer/general/active';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns Price Per Customer rule's enabled status
     *
     * @param string $scopeType
     * @return bool
     */
    public function isActive(string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_PRICE_PER_CUSTOMER_ACTIVE, $scopeType);
    }
}
