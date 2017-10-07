<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 30/09/17
 * Time: 10:32 AM
 */

namespace ScandicDesi\Checkout\Plugin\Controller\Cart;

use Magento\Checkout\Controller\Cart\Index;
use ScandicDesi\Checkout\Block\Cart;

class HeadingPlugin
{
    /**
     * @var Cart
     */
    private $cart = null;

    /**
     * HeadingPlugin constructor.
     * @param Cart $cart
     */
    public function __construct(
        Cart $cart
    ) {
        $this->cart = $cart;
    }

    /**
     * @param Index $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(Index $subject, $result)
    {
        /** @var \Magento\Theme\Block\Html\Title $pageHeading */
        $pageHeading = $result->getLayout()->getBlock('page.main.title');
        $heading = $pageHeading->getPageHeading();
        $qty = $this->cart->getSummaryQty();
        $itemText = $qty > 1 ? 'Items' : 'Item';
        
        $itemText = __($itemText);
        $headingQty = "";
        if($qty >= 1){
            $headingQty = __("(%1 %2)", $qty, $itemText);
        }
        $heading = __("%1 %2", $heading, $headingQty);
        $pageHeading->setPageTitle($heading);
        return $result;
    }
}
