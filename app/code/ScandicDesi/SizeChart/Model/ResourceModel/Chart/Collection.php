<?php
/**
 * Copyright © 2013-2017 ScandicDesi. All rights reserved.
 */

namespace ScandicDesi\SizeChart\Model\ResourceModel\Chart;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use ScandicDesi\SizeChart\Model\Chart;
use ScandicDesi\SizeChart\Model\ResourceModel\Chart as ChartResource;

/**
 * CMS Block Collection
 */
class Collection extends AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Chart::class,
            ChartResource::class
        );
    }
}
