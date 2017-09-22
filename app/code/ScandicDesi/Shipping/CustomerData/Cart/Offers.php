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
        $title = '';
        $message = '';
        $subTotal = (float)$this->checkoutSession->getQuote()->getBaseSubtotalWithDiscount();
        $itemsQty = (float)$this->checkoutSession->getQuote()->getItemsQty();
        $freeShippingThreshold = $this->getFreeShippingThresholdValue();
        if ($freeShippingThreshold !== null && $itemsQty) {
            $additional = $freeShippingThreshold - $subTotal;
            if ($additional > 0) {
                $formattedAdditionalValue = $this->pricingHelper->currency($additional, true, false);
                $shippingMessage = $this->config->getConfigValue('shipping', 'cart_offers_messages');
                $message = __(
                    $shippingMessage,
                    $formattedAdditionalValue
                );
                $title = __('Free Shipping');
            }
        }
        return [
            'title' => $title,
            'message' => $message
        ];
    }

    /**
     * @return float|null
     */
    private function getFreeShippingThresholdValue()
    {
        $freeShipping = $this->shipping->isFreeShippingAvailable();
        if ($freeShipping) {
            return (float)($this->shipping->getConfigData('free_shipping_subtotal'));
        }
        return null;
    }
}