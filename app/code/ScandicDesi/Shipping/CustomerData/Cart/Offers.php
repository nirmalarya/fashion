<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 22/09/17
 * Time: 10:07 AM
 */

namespace ScandicDesi\Shipping\CustomerData\Cart;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Checkout\Model\Session\Proxy as CheckoutSessionProxy;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use ScandicDesi\Shipping\Model\Carrier\Shipping;
use ScandicDesi\Shipping\Model\Config;

class Offers implements SectionSourceInterface
{
    /**
     * @var CheckoutSessionProxy
     */
    private $checkoutSession;
    /**
     * @var PricingHelper
     */
    private $pricingHelper;
    /**
     * @var Shipping
     */
    private $shipping;
    /**
     * @var Config
     */
    private $config;

    /**
     * Offers constructor.
     * @param CheckoutSessionProxy $checkoutSession
     * @param Shipping $shipping
     * @param Config $config
     * @param PricingHelper $pricingHelper
     */
    public function __construct(
        CheckoutSessionProxy $checkoutSession,
        Shipping $shipping,
        Config $config,
        PricingHelper $pricingHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->pricingHelper = $pricingHelper;
        $this->shipping = $shipping;
        $this->config = $config;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getSectionData()
    {
        // offers
        return [
            'shipping' => $this->getShippingOffer()
        ];
    }

    /**
     * @return array
     */
    public function getShippingOffer()
    {
        $response = [
            'title' => __('Free Shipping'),
            'message' => '',
            'free_shipping_threshold' => 0,
            'subtotal' => 0
        ];
        $freeShippingApplicable = $this->isFreeShippingOfferApplicable();
        if ($freeShippingApplicable) {
            $shippingMessage = $this->config->getConfigValue('shipping', 'cart_offers_messages');
            $response['message'] = __($shippingMessage);
            $response['free_shipping_threshold'] = $this->config->getConfigValue(
                'free_shipping_subtotal',
                'freeshipping',
                'carriers'
            );
            $response['subtotal'] = $this->checkoutSession->getQuote()->getBaseSubtotal();
        }
        return $response;
    }

    /**
     * Return true if the Free Shipping is applicable else return false
     * @return bool|float
     */
    public function isFreeShippingOfferApplicable()
    {
        $itemsQty = (float) $this->checkoutSession->getQuote()->getItemsQty();
        $isApplicable = $this->shipping->isFreeShippingAvailable();
        if ($isApplicable && $itemsQty) {
            return true;
        }
        return false;
    }
}
