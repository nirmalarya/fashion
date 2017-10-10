<?php
/**
 * Copyright © 2016 Echidna Inc. All rights reserved.
 * 
 */
namespace ScandicDesi\Catalog\Helper;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
/**
 * Catalog Product helper
 */
class Product extends \Magento\Catalog\Helper\Product
{
	 /**
     * @var WeltPixel\Quickview\Plugin\BlockProductList --  for custom button 
     */
	const XML_PATH_QUICKVIEW_ENABLED = 'weltpixel_quickview/general/enable_product_listing';
    const XML_PATH_QUICKVIEW_BUTTONSTYLE = 'weltpixel_quickview/general/button_style';
	
	
	/**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Catalog\Model\Attribute\Config $attributeConfig
     * @param array $reindexPriceIndexerData
     * @param array $reindexProductCategoryIndexerData
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Model\Attribute\Config $attributeConfig,
        $reindexPriceIndexerData,
        $reindexProductCategoryIndexerData,
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($context,
			$storeManager,
			$catalogSession,
			$assetRepo,
			$coreRegistry,
			$attributeConfig,
			$reindexPriceIndexerData,
			$reindexProductCategoryIndexerData,
			$productRepository,
			$categoryRepository
		);
		
    }

	public function quickViewCustomButtonHtml($product)
    {
		$result = '';
                
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $buttonStyle =  'weltpixel_quickview_button_' . $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONSTYLE,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $productUrl = $this->_storeManager->getStore()->getUrl('weltpixel_quickview/catalog_product/view', array('id' => $product->getId()));
            return $result . '<a class="weltpixel-quickview '.$buttonStyle.'" data-quickview-url=' . $productUrl . ' href="javascript:void(0);"><span>' . __("Quickview") . '</span></a>';
        }

        return $result;
    }
	
}