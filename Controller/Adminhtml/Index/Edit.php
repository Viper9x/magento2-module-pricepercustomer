<?php

namespace TuVan\PricePerCustomer\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;
use TuVan\PricePerCustomer\Model\PricePerCustomerFactory;
use TuVan\PricePerCustomer\Model\ResourceModel\PricePerCustomer as PricePerCustomerResource;

/**
 * Edit Rule action.
 */
class Edit extends \TuVan\PricePerCustomer\Controller\Adminhtml\Index implements HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var PricePerCustomerResource
     */
    private $pricePerCustomerResource;

    /**
     * @var PricePerCustomerFactory
     */
    private $pricePerCustomerFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param PricePerCustomerRepositoryInterface $pricePerCustomerRepository
     * @param PricePerCustomerFactory $pricePerCustomerFactory
     * @param PricePerCustomerResource $pricePerCustomerResource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        PricePerCustomerRepositoryInterface $pricePerCustomerRepository,
        PricePerCustomerFactory $pricePerCustomerFactory,
        PricePerCustomerResource $pricePerCustomerResource
    ) {
        parent::__construct($context, $pricePerCustomerRepository);
        $this->resultPageFactory = $resultPageFactory;
        $this->pricePerCustomerFactory =  $pricePerCustomerFactory;
        $this->pricePerCustomerResource =  $pricePerCustomerResource;
    }

    /**
     * Execute action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('rule_id');
        $model = $this->pricePerCustomerFactory->create();

        if ($id) {
            $this->pricePerCustomerResource->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $pageTitle = $model->getId() ? __('Edit Price Per Customer Rule') : __('New Price Per Customer Rule');
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('TuVan_PricePerCustomer::price_per_customer_manage');
        $resultPage->getConfig()->getTitle()->prepend(__('Price Per Customer Rule'));
        $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        return $resultPage;
    }
}
