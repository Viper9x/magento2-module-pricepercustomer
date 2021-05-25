<?php

namespace TuVan\PricePerCustomer\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use TuVan\PricePerCustomer\Api\Data;

/**
 * PricePerCustomer CRUD interface
 * @api
 */
interface PricePerCustomerRepositoryInterface
{
    /**
     * Save rule.
     *
     * @param Data\PricePerCustomerInterface $rule
     * @return Data\PricePerCustomerInterface
     * @throws LocalizedException
     */
    public function save(Data\PricePerCustomerInterface $rule);

    /**
     * Retrieve rule.
     *
     * @param int $id
     * @return Data\PricePerCustomerInterface
     * @throws LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve rules matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return Data\PricePerCustomerSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete rule.
     *
     * @param Data\PricePerCustomerInterface $rule
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(Data\PricePerCustomerInterface $rule);

    /**
     * Delete rule by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($id);
}
