<?php
/**
 * Copyright © 2013-2017 Echidna, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Echidna\Megamenu\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Source model for Echidna Megamenu templates
 */
class Template extends AbstractSource
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
            ['value' => '0', 'label' => __('Please select a template')],
            ['value' => '1', 'label' => __('1 Column')],
            ['value' => '2', 'label' => __('2 Columns')],
            ['value' => '3', 'label' => __('3 Columns')],
            ['value' => '4', 'label' => __('4 Columns')]
        ];
    }
}
