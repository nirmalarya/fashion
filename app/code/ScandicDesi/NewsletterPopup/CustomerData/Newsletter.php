<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 01/10/17
 * Time: 2:13 AM
 */

namespace ScandicDesi\NewsletterPopup\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;

class Newsletter implements SectionSourceInterface
{
    /**
     * @var CustomerSession\Proxy
     */
    private $customerSessionProxy;
    /**
     * @var SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * Newsletter constructor.
     * @param CustomerSession\Proxy $customerSessionProxy
     * @param SubscriberFactory $subscriberFactory
     */
    public function __construct(
        CustomerSession\Proxy $customerSessionProxy,
        SubscriberFactory $subscriberFactory
    ) {
        $this->customerSessionProxy = $customerSessionProxy;
        $this->subscriberFactory = $subscriberFactory;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getSectionData()
    {
        return [
            'subscribed' => $this->isCustomerSubscribed()
        ];
    }

    /**
     * @return bool
     */
    private function isCustomerSubscribed()
    {
        return false;
        $customerData = $this->customerSessionProxy->getCustomerData();
        $customerEmail = $customerData ? $customerData->getEmail() : '';
        if ($customerEmail) {
            /** @var Subscriber $subscription */
            $subscription = $this->subscriberFactory->create();
            $subscription->loadByEmail($customerEmail);
            if ($subscription->getEmail() && $subscription->isSubscribed()) {
                return true;
            }
        }
        return false;
    }
}