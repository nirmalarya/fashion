<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 10/09/17
 * Time: 7:47 PM
 */

namespace ScandicDesi\SizeChart\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Chart extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('scandicdesi_size_chart', 'entity_id');
    }
}