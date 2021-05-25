<?php

namespace TuVan\PricePerCustomer\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Create Rule action.
 */
class NewAction extends \TuVan\PricePerCustomer\Controller\Adminhtml\Index implements HttpGetActionInterface
{
    /**
     * Execute action.
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
