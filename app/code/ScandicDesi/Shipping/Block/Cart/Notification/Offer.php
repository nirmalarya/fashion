<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 22/09/17
 * Time: 8:27 AM
 */

namespace ScandicDesi\Shipping\Block\Cart\Notification;

use Magento\Framework\View\Element\Template;
use ScandicDesi\Shipping\Model\Config;

class Offer extends Template
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Offer constructor.
     * @param Template\Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $isCartOfferActive = $this->config->getConfigValue('active', 'cart_offers');
        $isShippingActive = $this->config->getConfigValue('active', 'freeshipping', 'carriers');
        // return output only if the shipping is active
        if ($isShippingActive && $isCartOfferActive) {
            return parent::_toHtml();
        }
    }
}