<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 10/09/17
 * Time: 8:06 PM
 */

namespace ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;

use Magento\Backend\Model\View\Result\Page as BackendResultPage;
use ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;

class Index extends SizeChart
{
    /**
     * Size Chart listing page
     *
     * @return BackendResultPage
     */
    public function execute()
    {
        /** @var BackendResultPage $page */
        $page = $this->resultPageFactory->create();

        $this->initPage($page)->getConfig()->getTitle()->prepend(__('Size Chart'));
        $this->dataPersistor->clear('scandicdesi_sizechart');

        return $page;
    }
}