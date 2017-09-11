<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 10/09/17
 * Time: 8:06 PM
 */

namespace ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;
use ScandicDesi\SizeChart\Model\Chart;

class Save extends SizeChart
{
    /**
     * Size Chart listing page
     *
     * @return Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');

            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Block::STATUS_ENABLED;
            }
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            /** @var Chart $model */
            $model = $this->initChart($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This chart no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->getResource()->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the chart.'));
                $this->dataPersistor->clear('scandicdesi_sizechart');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the chart.'));
            }

            $this->dataPersistor->set('scandicdesi_sizechart', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}