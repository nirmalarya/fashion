<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ScandicDesi\Catalog\Model\Attribute\Source\Category;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use ScandicDesi\Catalog\Model\Config\Source\Category\Type as CategoryConfigType;

class Type extends AbstractSource
{
    /** @var CategoryConfigType */
    private $configType;

    public function __construct(CategoryConfigType $configType)
    {
        $this->configType = $configType;
    }

    /**
     * Retrieve all attribute options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->configType->toOptionArray();
        }
        return $this->_options;
    }
}
