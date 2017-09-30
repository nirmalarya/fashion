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
        $freeShippingApplicable = $this->isFreeShippingOfferApplicable();
        if ($freeShippingApplicable) {
            $difference = $this->shipping->getFreeShippingThresholdDifference();
            $formattedAdditionalValue = $this->pricingHelper->currency($difference, true, false);
            $shippingMessage = $this->config->getConfigValue('shipping', 'cart_offers_messages');
            $message = __(
                $shippingMessage,
                $formattedAdditionalValue
            );
            $title = __('Free Shipping');
        }
        return [
            'title' => $title,
            'message' => $message
        ];
    }

    /**
     * Return true if the Free Shipping is applicable else return false
     * @return bool|float
     */
    public function isFreeShippingOfferApplicable()
    {
        $itemsQty = (float) $this->checkoutSession->getQuote()->getItemsQty();
        $isApplicable = $this->shipping->isFreeShippingApplicable();
        if (!$isApplicable && $itemsQty) {
            return true;
        }
        return false;
    }
}
