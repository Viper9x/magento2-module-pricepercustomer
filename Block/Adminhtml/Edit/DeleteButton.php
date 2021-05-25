<?php

namespace TuVan\PricePerCustomer\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;

/**
 * Represents delete button with pre-configured options
 *
 * Provide an ability to show confirmation message on click on the "Delete" button
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var PricePerCustomerRepositoryInterface
     */
    protected $pricePerCustomerRepository;

    /**
     * @param Context $context
     * @param PricePerCustomerRepositoryInterface $pricePerCustomerRepository
     */
    public function __construct(
        Context $context,
        PricePerCustomerRepositoryInterface $pricePerCustomerRepository
    ) {
        $this->context = $context;
        $this->pricePerCustomerRepository = $pricePerCustomerRepository;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     * @throws LocalizedException
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getRuleId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to delete this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\', {data: {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * Return the current Rule Id
     *
     * @return int|null
     * @throws LocalizedException
     */
    public function getRuleId()
    {
        try {
            return $this->pricePerCustomerRepository->getById(
                $this->context->getRequest()->getParam('rule_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * URL to send delete requests to
     *
     * @return string
     * @throws LocalizedException
     */
    public function getDeleteUrl()
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['rule_id' => $this->getRuleId()]);
    }
}
