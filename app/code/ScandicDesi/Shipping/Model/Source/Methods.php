<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 16/09/17
 * Time: 10:34 PM
 */

namespace ScandicDesi\Shipping\Model\Source;

use Magento\Framework\Option\ArrayInterface;
use ScandicDesi\Shipping\Model\Carrier\Shipping;

class Methods implements ArrayInterface
{
    /**
     * @var Shipping
     */
    private $shipping;

    /**
     * Methods constructor.
     * @param Shipping $shipping
     */
    public function __construct(Shipping $shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $methods = $this->shipping->getCode();
        $options = [];
        foreach ($methods as $code => $label) {
            $options [] = [
                'value' => $code,
                'label' => $label
            ];
        }
        return $options;
    }
}