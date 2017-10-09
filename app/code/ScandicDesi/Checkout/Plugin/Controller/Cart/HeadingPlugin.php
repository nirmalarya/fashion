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
        
        if($qty >= 1) {
            $heading = __("%1 (%2 %3)", $heading, $qty, $itemText);
        }

        $pageHeading->setPageTitle($heading);
        return $result;
    }
}
