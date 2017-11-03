<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 07/09/17
 * Time: 10:44 PM
 */

namespace ScandicDesi\Catalog\Model\Config\Source\Category;

use Magento\Framework\Option\ArrayInterface;

class Type implements ArrayInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '',
                'label' => ''
            ],
            [
                'value' => 'occasion',
                'label' => 'Occasion'
            ],
            [
                'value' => 'fabric',
                'label' => 'Fabric'
            ],
            [
                'value' => 'style',
                'label' => 'Style'
            ]
        ];
    }
}