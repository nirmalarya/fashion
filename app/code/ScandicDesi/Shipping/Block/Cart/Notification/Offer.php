<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 22/09/17
 * Time: 8:27 AM
 */

namespace ScandicDesi\Shipping\Block\Cart\Notification;

use Magento\Checkout\Model\Session\Proxy as CheckoutSessionProxy;
use Magento\Framework\View\Element\Template;
use ScandicDesi\Shipping\Model\Config;

class Offer extends Template
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var CheckoutSessionProxy
     */
    private $checkoutSession;

    /**
     * Offer constructor.
     * @param Template\Context $context
     * @param Config $config
     * @param CheckoutSessionProxy $checkoutSession
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        CheckoutSessionProxy $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $isCartOfferActive = $this->config->getConfigValue('active', 'cart_offers');
        $isShippingActive = $this->config->getConfigValue('active', 'freeshipping', 'carriers');
        // return output only if the shipping is active
        if ($isShippingActive && $isCartOfferActive && $this->checkoutSession->getQuote()->getItemsQty() > 0) {
            return parent::_toHtml();
        }
    }
}