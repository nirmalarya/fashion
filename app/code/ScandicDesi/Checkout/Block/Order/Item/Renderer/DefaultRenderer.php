<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

// namespace Magento\Sales\Block\Order\Item\Renderer;
namespace ScandicDesi\Checkout\Block\Order\Item\Renderer;

use Magento\Sales\Model\Order\CreditMemo\Item as CreditMemoItem;
use Magento\Sales\Model\Order\Invoice\Item as InvoiceItem;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Order item render block
 */
class DefaultRenderer extends \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer
{    
    /**
     * @var \Magento\Checkout\Block\Cart\Item\Renderer
     */
    protected $_checkoutCartItemRenderer;
    
    /**
     * @var \Magento\Checkout\Block\Cart\Item\Renderer
     */
    protected $_productRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Checkout\Block\Cart\Item\Renderer  $checkoutCartItemRenderer,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        array $data = []
    ) {
        $this->_checkoutCartItemRenderer = $checkoutCartItemRenderer;
        $this->_productRepository = $productRepository;
        parent::__construct($context,$string,$productOptionFactory, $data);
    }

    /**
     * Return product object of order item.
     *
     * @return string
     */
    public function getProductByProductId($id)
	{
		return $this->_productRepository->getById($id);
	}
    
    /**
     * Return product image of order item.
     *
     * @return string | null
     */
    public function getProductImage()
    {   
        $imageHtml = null;
        $itemId = $this->getItem()->getId();
        if($itemId){
            $product =  $this->getProductByProductId($itemId);
            // $imageHtml = $this->_checkoutCartItemRenderer->getImage($product, 'cart_page_product_thumbnail')->toHtml();
            $imageHtml = $this->_checkoutCartItemRenderer->getImage($product, 'mini_cart_product_thumbnail')->toHtml();
        }
        return $imageHtml;
    }           
}
