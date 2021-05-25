<?php
namespace TuVan\PricePerCustomer\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;

/**
 * Index action.
 */
class Index extends \TuVan\PricePerCustomer\Controller\Adminhtml\Index implements HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param PricePerCustomerRepositoryInterface $pricePerCustomerRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        PricePerCustomerRepositoryInterface $pricePerCustomerRepository
    ) {
        parent::__construct($context, $pricePerCustomerRepository);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Execute action.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('TuVan_PricePerCustomer::price_per_customer_manage')
            ->getConfig()->getTitle()->prepend(__('Price Per Customer Rules'));
        return $resultPage;
    }
}
