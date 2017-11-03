<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 10/09/17
 * Time: 8:06 PM
 */

namespace ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;

use Magento\Backend\Model\View\Result\Page as BackendResultPage;
use Magento\Backend\Model\View\Result\Redirect as BackendResultRedirect;
use Magento\Framework\Controller\Result\Redirect;
use ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;

class Edit extends SizeChart
{
    /**
     * Size Chart listing page
     *
     * @return BackendResultPage|Redirect
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('entity_id');
        $model = $this->initChart($id);

        // 2. Initial checking
        if ($id) {
            if (!$model->getId()) {
                $this->messageManager->addError(__('This chart no longer exists.'));
                /** @var BackendResultRedirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('sizechart', $model);

        // 5. Build edit form
        /** @var BackendResultPage $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Block') : __('New Chart'),
            $id ? __('Edit Block') : __('New Chart')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('SizeCharts'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Chart'));
        return $resultPage;
    }
}