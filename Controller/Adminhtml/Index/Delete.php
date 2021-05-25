<?php

namespace TuVan\PricePerCustomer\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Delete Rule action.
 */
class Delete extends \TuVan\PricePerCustomer\Controller\Adminhtml\Index implements HttpPostActionInterface
{
    /**
     * Execute action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('rule_id', false);
        if ($id) {
            try {
                $this->pricePerCustomerRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The Rule has been deleted.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong deleting this rule.'));
            }
        }
        return $resultRedirect->setPath('price_per_customer/*/');
    }
}
