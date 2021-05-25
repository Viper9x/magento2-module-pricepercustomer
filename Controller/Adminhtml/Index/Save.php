<?php

namespace TuVan\PricePerCustomer\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use TuVan\PricePerCustomer\Api\PricePerCustomerRepositoryInterface;
use TuVan\PricePerCustomer\Model\PricePerCustomerFactory;

/**
 * Save Rule action.
 */
class Save extends \TuVan\PricePerCustomer\Controller\Adminhtml\Index implements HttpPostActionInterface
{
    /**
     * @var TimezoneInterface
     */
    private $_timezone;

    /**
     * @var Date
     */
    protected $_dateFilter;

    /**
     * @var PricePerCustomerFactory
     */
    private $pricePerCustomerFactory;

    /**
     * @var PricePerCustomerRepositoryInterface
     */
    protected $pricePerCustomerRepository;

    /**
     * @param Context $context
     * @param Date $dateFilter
     * @param TimezoneInterface $timezone
     * @param PricePerCustomerFactory $pricePerCustomerFactory
     * @param PricePerCustomerRepositoryInterface $pricePerCustomerRepository
     */
    public function __construct(
        Context $context,
        Date $dateFilter,
        TimezoneInterface $timezone,
        PricePerCustomerFactory $pricePerCustomerFactory,
        PricePerCustomerRepositoryInterface $pricePerCustomerRepository
    ) {
        $this->_timezone = $timezone;
        $this->_dateFilter = $dateFilter;
        $this->pricePerCustomerFactory = $pricePerCustomerFactory;
        parent::__construct($context, $pricePerCustomerRepository);
    }

    /**
     * Execute action.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     * @throws \Exception
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {
            /** @var \TuVan\PricePerCustomer\Model\PricePerCustomer $model */
            $model = $this->pricePerCustomerFactory->create();

            $id = (int) $this->getRequest()->getParam('rule_id');
            if ($id) {
                try {
                    $model = $this->pricePerCustomerRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This rule no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (empty($data['from_date'])) {
                $data['from_date'] = $this->_timezone->formatDate();
            }

            $filterValues = ['from_date' => $this->_dateFilter];
            if ($this->getRequest()->getParam('to_date')) {
                $filterValues['to_date'] = $this->_dateFilter;
            }
            $inputFilter = new \Zend_Filter_Input(
                $filterValues,
                [],
                $data
            );
            $data = $inputFilter->getUnescaped();

            $validateResult = $model->validateData(new \Magento\Framework\DataObject($data));
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addErrorMessage($errorMessage);
                }
                $this->_getSession()->setRuleFormData($data);
                $this->processRedirectAfterFailureSave($resultRedirect, $id);
                return $resultRedirect;
            }

            $model->setData($data);

            try {
                $this->pricePerCustomerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('The Rule has been saved.'));
                $this->_getSession()->unsRuleFormData();
                $this->processRedirectAfterSuccessSave($resultRedirect, $id);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                // Set original request data to session
                $this->_getSession()->setRuleFormData($this->getRequest()->getPostValue());
                $this->processRedirectAfterFailureSave($resultRedirect, $id);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the rule data.')
                );
                // Set original request data to session
                $this->_getSession()->setRuleFormData($this->getRequest()->getPostValue());
                $this->processRedirectAfterFailureSave($resultRedirect, $id);
            }
        }
        return $resultRedirect;
    }

    /**
     * Get redirect url after rule save.
     *
     * @param Redirect $resultRedirect
     * @param int $id
     * @return void
     */
    private function processRedirectAfterSuccessSave(Redirect $resultRedirect, $id)
    {
        if ($this->getRequest()->getParam('back')) {
            $resultRedirect->setPath('*/*/edit', ['rule_id' => $id, '_current' => true]);
        } else {
            $resultRedirect->setPath('*/*/');
        }
    }

    /**
     * Get redirect url after unsuccessful rule save.
     *
     * @param Redirect $resultRedirect
     * @param int $id
     * @return void
     */
    private function processRedirectAfterFailureSave(Redirect $resultRedirect, $id)
    {
        if (!empty($id)) {
            $resultRedirect->setPath('*/*/edit', ['rule_id' => $id, '_current' => true]);
        } else {
            $resultRedirect->setPath('*/*/new');
        }
    }
}
