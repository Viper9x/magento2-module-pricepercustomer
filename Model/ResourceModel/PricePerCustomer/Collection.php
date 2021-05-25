<?php

namespace TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer as PricePerCustomerResource;
use TuVan\PricePerCustomer\Model\PricePerCustomer;

/**
 * Price Per Customer Rule collection
 */
class Collection extends AbstractCollection
{
    /**
     * Init collection and determine table names
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(PricePerCustomer::class, PricePerCustomerResource::class);
    }
}
