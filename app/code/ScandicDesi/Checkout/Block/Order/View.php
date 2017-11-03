<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ScandicDesi\Checkout\Block\Order;

/**
 * Sales order view block
 */
class View extends \Magento\Sales\Block\Order\View // \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'Magento_Sales::order/view.phtml';
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_salesOrder;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Payment\Helper\Data $paymentHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Checkout\Model\Session  $checkoutSession,
        \Magento\Sales\Model\Order  $salesOrder,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_salesOrder = $salesOrder;
        parent::__construct($context, $registry, $httpContext, $paymentHelper, $data);
    }

       

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {   
        $checkoutSession = $this->_checkoutSession->getLastRealOrder();
        
        if($this->_coreRegistry->registry('current_order')){
            $this->_coreRegistry->unregister('current_order');
        }
        if($checkoutSession && !$this->_coreRegistry->registry('current_order')){
            $getOrderId = $checkoutSession->getEntityId();
            // $orderId = (int)$getOrderId;
            $orderId = (int) $checkoutSession->getIncrementId();
            $current_order = $this->_salesOrder->loadByIncrementId($orderId);
            $this->_coreRegistry->register('current_order', $current_order); 
        }
        
        return  $this->_coreRegistry->registry('current_order');
    }

    
}
