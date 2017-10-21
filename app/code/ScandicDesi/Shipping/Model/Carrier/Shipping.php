<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 16/09/17
 * Time: 8:24 PM
 */

namespace ScandicDesi\Shipping\Model\Carrier;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

/**
 * Shipping methods PostNord and Bring
 *
 * @package ScandicDesi\Shipping\Model\Carrier
 */
class Shipping extends AbstractCarrier implements CarrierInterface
{
    const SHIPPING_FREE = 'scandicdesi_free';
    const SHIPPING_STANDARD = 'scandicdesi_standard';
    const SHIPPING_EXPRESS = 'scandicdesi_express';
    /**
     * @var string
     */
    protected $_code = 'scandicdesi';
    /**
     * @var Result
     */
    private $result;
    /**
     * @var ResultFactory
     */
    private $rateResultFactory;
    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;
    /**
     * @var ErrorFactory
     */
    private $rateErrorFactory;
    /**
     * @var CustomerSession\Proxy
     */
    private $customerSessionProxy;
    /**
     * @var RateRequest|null
     */
    private $request;
    /**
     * @var float|null
     */
    private $freeShippingThresholdDifference = null;
    /**
     * @var CheckoutSession\Proxy
     */
    private $checkoutSession;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Shipping constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param CustomerSession\Proxy $customerSessionProxy
     * @param CheckoutSession\Proxy $checkoutSession
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        CustomerSession\Proxy $customerSessionProxy,
        CheckoutSession\Proxy $checkoutSession,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->rateErrorFactory = $rateErrorFactory;
        $this->customerSessionProxy = $customerSessionProxy;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getConfigCode()
    {
        return $this->_code;
    }

    /**
     * @return bool
     */
    public function isFreeShippingAvailable()
    {
        if ($this->scopeConfig->getValue('carriers/freeshipping/active')) {
            return true;
        }
        return false;
    }

    /**
     * Return true if the Free Shipping is applicable else return false
     * @return bool|float
     */
    public function isFreeShippingApplicable()
    {
        $thresholdDifference = $this->getFreeShippingThresholdDifference();
        if ($this->isFreeShippingAvailable() &&
            $thresholdDifference <= 0
        ) {
            return true;
        }
        return false;
    }

    /**
     * Get the free shipping threshold and subtotal difference
     */
    public function getFreeShippingThresholdDifference()
    {
        if ($this->freeShippingThresholdDifference === null) {
            if ($this->getRequest() !== null) {
                $subTotal = (float) $this->getRequest()->getBaseSubtotalInclTax();
            } else {
                $subTotal = (float) $this->checkoutSession->getQuote()->getBaseSubtotal();
            }
            $freeShippingThreshold = (float) $this->scopeConfig->getValue('carriers/freeshipping/free_shipping_subtotal');
            $this->freeShippingThresholdDifference = $freeShippingThreshold - $subTotal;
        }
        return (float) $this->freeShippingThresholdDifference;
    }

    /**
     * Get configuration data of carrier
     *
     * @param string $code
     * @return array|bool|mixed
     */
    public function getCode($code = '')
    {
        $codes = [
            self::SHIPPING_STANDARD => $this->getConfigData(self::SHIPPING_STANDARD . '_name'),
            self::SHIPPING_EXPRESS => $this->getConfigData(self::SHIPPING_EXPRESS . '_name')
        ];

        if ($code == '') {
            return $codes;
        }

        if (!isset($codes[$code])) {
            return false;
        } else {
            return $codes[$code];
        }
    }

    /**
     * Set result for request
     *
     * @return $this
     */
    public function setResult()
    {
        /** @var Result */
        $this->result = $this->getResult();
        $methods = $this->getAllowedMethods();

        foreach ($methods as $code => $label) {
            /** @var Method $method */
            $method = $this->rateMethodFactory->create();

            $method->setCarrier($this->getCarrierCode());
            $title = $this->getConfigData($code . '_title');
            $method->setCarrierTitle($title);
            $method->setMethod($code);
            $method->setMethodTitle($label);
            $amount = $this->getConfigData($code . '_price');
            $method->setCost($amount);
            $method->setPrice($amount);
            $this->result->append($method);
        }

        return $this;
    }

    /**
     * Get result of request
     *
     * @return Result
     */
    public function getResult()
    {
        if (!$this->result) {
            $this->result = $this->rateResultFactory->create();
        }
        return $this->result;
    }

    /**
     * Get allowed methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        $allowedMethods = $this->getConfigData('allowed_methods');
        $allowedMethods = explode(',', $allowedMethods);
        $allowed = [];
        foreach ($allowedMethods as $method) {
            if ($method) {
                $allowed[$method] = $this->getCode($method);
            }
        }
        $allowed = $this->filterMethods($allowed);
        return $allowed;
    }

    /**
     * Get allowed methods
     *
     * @param $methods
     * @return array
     */
    private function filterMethods($methods)
    {
        if ($this->isFreeShippingApplicable()) {
            unset($methods[self::SHIPPING_STANDARD]);
        }
        return $methods;
    }

    /**
     * @param $request
     * @return $this
     */
    private function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return RateRequest
     */
    private function getRequest()
    {
        return $this->request;
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->isActive()) {
            return false;
        }

        $this->setRequest($request);
        $this->setResult();

        return $this->getResult();
    }

    /**
     * Get error messages
     *
     * @return bool|Error
     */
    private function getErrorMessage()
    {
        if ($this->getConfigData('showmethod')) {
            /** @var Error $error */
            $error = $this->rateErrorFactory->create();
            $error->setCarrier($this->getCarrierCode());
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            return $error;
        } else {
            return false;
        }
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return boolean
     * @api
     */
    public function isTrackingAvailable()
    {
        return false;
    }
}