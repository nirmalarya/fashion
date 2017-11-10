<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 30/09/17
 * Time: 2:23 PM
 */

namespace ScandicDesi\Checkout\Block;

use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\View\Element\Template;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Store\Model\ScopeInterface;

class Cart extends Template
{
    /**
     * @var TotalsInterface|null
     */
    private $totals = null;
    /**
     * @var CartInterface|null
     */
    private $cart = null;
    /**
     * @var int|null
     */
    private $summaryQty = null;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var CheckoutSession\Proxy
     */
    private $checkoutSessionProxy;
    /**
     * @var CartTotalRepositoryInterface
     */
    private $cartTotalRepository;
    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * HeadingPlugin constructor.
     * @param Template\Context $context
     * @param CartRepositoryInterface $cartRepository
     * @param CartTotalRepositoryInterface $cartTotalRepository
     * @param CheckoutSession\Proxy $checkoutSessionProxy
     * @param PriceHelper $priceHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CartRepositoryInterface $cartRepository,
        CartTotalRepositoryInterface $cartTotalRepository,
        CheckoutSession\Proxy $checkoutSessionProxy,
        PriceHelper $priceHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->cartRepository = $cartRepository;
        $this->checkoutSessionProxy = $checkoutSessionProxy;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->priceHelper = $priceHelper;
    }

    /**
     * Get the current quote or cart object
     *
     * @return CartInterface
     */
    private function getCart()
    {
        if ($this->cart === null) {
            try {
                $quoteId = (int)$this->checkoutSessionProxy->getQuoteId();
                $this->cart = $this->cartRepository->get($quoteId);
            } catch (LocalizedException $e) {
                $this->cart = null;
            }
        }

        return $this->cart;
    }

    /**
     * @return int
     */
    public function getSummaryQty()
    {
        $cart = $this->getCart();
        if ($this->summaryQty === null && $cart !== null) {
            $useQty = $this->_scopeConfig->getValue(
                'checkout/cart_link/use_qty',
                ScopeInterface::SCOPE_STORE
            );
            $this->summaryQty = $useQty ? $cart->getItemsQty() : $cart->getItemsCount();
        }
        return (int) $this->summaryQty;
    }

    /**
     * @return TotalsInterface
     */
    public function getTotals()
    {
        if ($this->totals == null) {
            $this->totals = $this->cartTotalRepository->get($this->getCart()->getId());
        }
        return $this->totals;
    }

    /**
     * Grand Total amount
     * @param bool $format
     * @return string
     */
    public function getGrandTotal($format = false)
    {
        $grandTotal = $this->getTotals()->getGrandTotal();
        if ($format) {
            $grandTotal = $this->formatPrice($grandTotal);
        }
        return $grandTotal;
    }

    /**
     * @param $price
     * @return string
     */
    public function formatPrice($price)
    {
        $price = $this->priceHelper->currency($price, true, false);
        return $price;
    }
}
