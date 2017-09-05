<?php
/**
 * Copyright © 2013-2017 Echidna, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Echidna\Megamenu\Model\Config\Source\Template;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Source model for Echidna Megamenu templates content type
 */
class Type extends AbstractSource
{
    /**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->toOptionArray();
        }
        return $this->_options;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Please select a content type')],
            ['value' => '1', 'label' => __('Category List')],
            ['value' => '2', 'label' => __('Static Block')],
            ['value' => '3', 'label' => __('Custom Content')]
        ];
    }
}
