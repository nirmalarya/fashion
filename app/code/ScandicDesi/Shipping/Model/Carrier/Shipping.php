<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 16/09/17
 * Time: 8:24 PM
 */

namespace ScandicDesi\Shipping\Model\Carrier;

use Magento\Customer\Model\Session as CustomerSession;
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
    const FREE_SHIPPING = 'scandicdesi_free';
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
     * Shipping constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param CustomerSession\Proxy $customerSessionProxy
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        CustomerSession\Proxy $customerSessionProxy,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->rateErrorFactory = $rateErrorFactory;
        $this->customerSessionProxy = $customerSessionProxy;
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
        if ($this->getConfigData('free_shipping_enable')) {
            $customerGroupId = $this->customerSessionProxy->getCustomerGroupId();
            $freeShippingCustomerGroups = $this->getConfigData('free_shipping_customer_groups');
            $freeShippingCustomerGroups = explode(',', $freeShippingCustomerGroups);
            if (in_array($customerGroupId, $freeShippingCustomerGroups)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get configuration data of carrier
     *
     * @param string $code
     * @param bool $includeFree
     * @return array|bool|mixed
     */
    public function getCode($code = '', $includeFree = false)
    {
        $codes = [
            'scandicdesi_express' => __('Express Shipping')
        ];

        if ($includeFree && $this->isFreeShippingAvailable()) {
            $codes[self::FREE_SHIPPING] = __('Free Shipping');
        }

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
        $this->result = $this->rateResultFactory->create();

        $methods = $this->getAllowedMethods();
        foreach ($methods as $code => $label) {
            /** @var Method $method */
            $method = $this->rateMethodFactory->create();

            $method->setCarrier($this->getCarrierCode());
            $method->setCarrierTitle($this->getConfigData('title'));
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
     * get allowed methods
     * @return array
     */
    public function getAllowedMethods()
    {
        $allowedMethods = $this->getConfigData('allowed_methods');
        $allowedMethods = explode(',', $allowedMethods);
        $allowed = [];
        foreach ($allowedMethods as $method) {
            $allowed[$method] = $this->getCode($method);
        }
        // add free shipping if applicable
        if ($this->isFreeShippingAvailable()) {
            $allowed[self::FREE_SHIPPING] = $this->getCode(self::FREE_SHIPPING, true);
        }
        return $allowed;
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

        if (!$this->getConfigData('name')) {
            return $this->getErrorMessage();
        }

        $this->setResult();

        return $this->getResult();
    }

    /**
     * Get error messages
     *
     * @return bool|Error
     */
    protected function getErrorMessage()
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