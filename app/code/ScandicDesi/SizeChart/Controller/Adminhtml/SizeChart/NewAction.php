<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 11/09/17
 * Time: 8:16 PM
 */

namespace ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use ScandicDesi\SizeChart\Controller\Adminhtml\SizeChart;
use ScandicDesi\SizeChart\Model\ChartFactory;
use ScandicDesi\SizeChart\Model\Config;

class NewAction extends SizeChart
{
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * NewAction constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ChartFactory $chartFactory
     * @param RedirectFactory $resultRedirectFactory
     * @param DataPersistorInterface $dataPersistor
     * @param Registry $coreRegistry
     * @param Config $config
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        ChartFactory $chartFactory,
        RedirectFactory $resultRedirectFactory,
        DataPersistorInterface $dataPersistor,
        Registry $coreRegistry,
        Config $config,
        ForwardFactory $resultForwardFactory
    ) {
        parent::__construct(
            $context,
            $resultPageFactory,
            $chartFactory,
            $resultRedirectFactory,
            $dataPersistor,
            $coreRegistry,
            $config
        );
        $this->resultForwardFactory = $resultForwardFactory;
    }

    /**
     * Create new CMS block
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}