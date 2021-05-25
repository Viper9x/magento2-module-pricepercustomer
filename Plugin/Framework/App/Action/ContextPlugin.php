<?php
namespace TuVan\PricePerCustomer\Plugin\Framework\App\Action;

class ContextPlugin
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
    }

    /**
     * Set customer ID to HTTP context
     *
     * @param \Magento\Framework\App\ActionInterface $subject
     * @param \Magento\Framework\App\RequestInterface $request
     * @return void
     */
    public function beforeDispatch(
        \Magento\Framework\App\ActionInterface $subject,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->httpContext->setValue(
            'customer_id',
            $this->customerSession->getCustomerId(),
            false
        );
    }
}
