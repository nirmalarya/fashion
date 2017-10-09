<?php

// namespace Dibs\Flexwin\Model;

namespace ScandicDesi\Dibs\Model;

use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;

class ConfigProvider extends \Dibs\Flexwin\Model\ConfigProvider
{
    protected $method;
    
   
    
    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\View\Asset\Repository $assetRepo

    )  {   
        
        $this->method = $paymentHelper->getMethodInstance(self::METHOD_CODE); 
        parent::__construct($paymentHelper, $escaper, $urlInterface, $assetRepo);
    }

    public function getConfig()
    {
        $config =  parent::getConfig();
        if($this->method->getConfigData('scandicdesi_tc_enable') == 1 && $this->method->getConfigData('scandicdesi_tc_text') && $this->method->getConfigData('scandicdesi_tc_link')) {
            
            $paytypes = $config['payment']['dibsFlexwin']['paytype'];
            
            foreach ($paytypes as $k => $v ){
                
                $config['payment']['dibsFlexwin']['paytype'][$k]['terms_and_condition'] = [ 
                    'scandicdesi_tc_enable' => $this->method->getConfigData('scandicdesi_tc_enable'),
                    'scandicdesi_tc_text' => $this->method->getConfigData('scandicdesi_tc_text'),
                    'scandicdesi_tc_link' => $this->method->getConfigData('scandicdesi_tc_link')
                ];
            }
            
        }
        return $config;
    }
}