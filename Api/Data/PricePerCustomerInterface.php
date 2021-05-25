<?php

namespace TuVan\PricePerCustomer\Api\Data;

/**
 * Interface PricePerCustomer
 * @api
 */
interface PricePerCustomerInterface
{
    /**
     * Get rule id
     *
     * @return int
     */
    public function getId();

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set the customer the rule apply to
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Set the product the rule apply to
     *
     * @param int $customerId
     * @return $this
     */
    public function setProductId($customerId);

    /**
     * Get product id
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set special price
     *
     * @param float $specialPrice
     * @return $this
     */
    public function setSpecialPrice($specialPrice);

    /**
     * Get specialPrice
     *
     * @return float
     */
    public function getSpecialPrice();

    /**
     * Get the start date when the rule is active
     *
     * @return string|null
     */
    public function getFromDate();

    /**
     * Set the start date when the rule is active
     *
     * @param string $fromDate
     * @return $this
     */
    public function setFromDate($fromDate);

    /**
     * Get the end date when the rule is active
     *
     * @return string|null
     */
    public function getToDate();

    /**
     * Set the end date when the rule is active
     *
     * @param string $fromDate
     * @return $this
     */
    public function setToDate($fromDate);
}
