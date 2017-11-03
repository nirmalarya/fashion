<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 10/09/17
 * Time: 8:06 PM
 */

namespace ScandicDesi\SizeChart\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page as BackendResultPage;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use ScandicDesi\SizeChart\Model\Chart;
use ScandicDesi\SizeChart\Model\ChartFactory;
use ScandicDesi\SizeChart\Model\Config;

abstract class SizeChart extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ScandicDesi_SizeChart::size_chart';
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var ChartFactory
     */
    protected $chartFactory;
    /**
     * @var RedirectFactory
     */
    protected $resutlRedirctFactory;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * SizeChart constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param ChartFactory $chartFactory
     * @param RedirectFactory $resultRedirectFactory
     * @param DataPersistorInterface $dataPersistor
     * @param Registry $coreRegistry
     * @param Config $config
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        ChartFactory $chartFactory,
        RedirectFactory $resultRedirectFactory,
        DataPersistorInterface $dataPersistor,
        Registry $coreRegistry,
        Config $config
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->chartFactory = $chartFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->resutlRedirctFactory = $resultRedirectFactory;
        $this->dataPersistor = $dataPersistor;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param $id
     * @return Chart
     */
    public function initChart($id)
    {
        /** @var Chart $model */
        $model = $this->chartFactory->create();
        if ($id) {
            $model->getResource()->load($model, $id);
        }
        return $model;
    }

    /**
     * @param $page
     * @return BackendResultPage
     */
    protected function initPage(BackendResultPage $page)
    {
        $page->setActiveMenu('ScandicDesi_SizeChart::size_chart')
            ->addBreadcrumb(__('ScandicDesi'), __('ScandicDesi'));
        return $page;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        $acl = parent::_isAllowed();
        $isModuleEnabled = $this->config->isEnabled();
        return $acl && $isModuleEnabled;
    }
}