<?php

namespace TuVan\PricePerCustomer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PricePerCustomerSearchResultsInterface
 * @api
 */
interface PricePerCustomerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get rules.
     *
     * @return \TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface[]
     */
    public function getItems();

    /**
     * Set rules.
     *
     * @param \TuVan\PricePerCustomer\Api\Data\PricePerCustomerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
