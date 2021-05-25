<?php

namespace TuVan\PricePerCustomer\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\InventoryAdminUi\Ui\Component\MassAction\Filter;
use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;

/**
 * Mass Delete action.
 */
class MassDelete extends \TuVan\PricePerCustomer\Controller\Adminhtml\Index implements HttpPostActionInterface
{
    /**
     * @var Filter
     */
    private $massActionFilter;

    /**
     * @param Action\Context $context
     * @param PricePerCustomerRepositoryInterface $pricePerCustomerRepository
     * @param Filter $massActionFilter
     */
    public function __construct(
        Action\Context $context,
        PricePerCustomerRepositoryInterface $pricePerCustomerRepository,
        Filter $massActionFilter
    ) {
        parent::__construct($context, $pricePerCustomerRepository);
        $this->massActionFilter = $massActionFilter;
    }

    /**
     * Execute action.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute()
    {
        if ($this->getRequest()->isPost() !== true) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));

            return $this->resultRedirectFactory->create()->setPath('*/*');
        }

        $ruleIds = $this->massActionFilter->getIds();
        $deletedItemsCount = 0;
        foreach ($ruleIds as $id) {
            try {
                $id = (int) $id;
                $this->pricePerCustomerRepository->deleteById($id);
                $deletedItemsCount++;
            } catch (CouldNotDeleteException $e) {
                $errorMessage = __('[ID: %1] ', $id) . $e->getMessage();
                $this->messageManager->addErrorMessage($errorMessage);
            }
        }
        $this->messageManager->addSuccessMessage(__('You deleted %1 rule(s).', $deletedItemsCount));

        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}
