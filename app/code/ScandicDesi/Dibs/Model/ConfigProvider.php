<?php

// namespace Dibs\Flexwin\Model;

namespace ScandicDesi\Dibs\Model;

use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;

class ConfigProvider extends \Dibs\Flexwin\Model\ConfigProvider
{
    protected $method;
    protected $_filterProvider;
    
   
    
    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper $escaper,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider

    )  {   
        $this->_filterProvider = $filterProvider;
        
        $this->method = $paymentHelper->getMethodInstance(self::METHOD_CODE); 
        parent::__construct($paymentHelper, $escaper, $urlInterface, $assetRepo);
    }

    public function getConfig()
    {
        $config =  parent::getConfig();
                
            
        $paytypes = $config['payment']['dibsFlexwin']['paytype'];
            
        foreach ($paytypes as $k => $v ){
            
            if($this->method->getConfigData('scandicdesi_tc_enable') == 1 && $this->method->getConfigData('scandicdesi_desc_text')) {
                
                $scandicdesi_desc_text = $this->_filterProvider->getBlockFilter()->filter($this->method->getConfigData('scandicdesi_desc_text'));
                $config['payment']['dibsFlexwin']['paytype'][$k]['scandicdesi_desc_text'] =  $scandicdesi_desc_text;            
                $config['payment']['dibsFlexwin']['paytype'][$k]['scandicdesi_tc_enable'] = $this->method->getConfigData('scandicdesi_tc_enable');
            }
            
        }
        return $config;
    }
}