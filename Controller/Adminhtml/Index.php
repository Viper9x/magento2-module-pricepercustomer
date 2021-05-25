<?php

namespace TuVan\PricePerCustomer\Controller\Adminhtml;

use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;

/**
 * Rule admin controller.
 */
abstract class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see \Magento\Backend\App\Action\_isAllowed()
     */
    const ADMIN_RESOURCE = 'TuVan_PricePerCustomer::manage';

    /**
     * @var PricePerCustomerRepositoryInterface
     */
    protected $pricePerCustomerRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param PricePerCustomerRepositoryInterface $pricePerCustomerRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PricePerCustomerRepositoryInterface $pricePerCustomerRepository
    ) {
        parent::__construct($context);
        $this->pricePerCustomerRepository = $pricePerCustomerRepository;
    }
}
