<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 11/09/17
 * Time: 10:41 PM
 */

namespace ScandicDesi\SizeChart\Block;

use Magento\Catalog\Model\Product;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use ScandicDesi\SizeChart\Model\Chart as ChartModel;
use ScandicDesi\SizeChart\Model\ChartFactory;
use ScandicDesi\SizeChart\Model\Config;

class Chart extends Template
{
    const TEMPLATE = 'ScandicDesi_SizeChart::product/view/size_chart.phtml';
    /**
     * @var ChartFactory
     */
    private $chartFactory;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Registry
     */
    private $coreRegistry;
    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * Chart constructor.
     * @param Template\Context $context
     * @param ChartFactory $chartFactory
     * @param Config $config
     * @param Registry $coreRegistry
     * @param FilterProvider $filterProvider
     * @param array $data
     * @internal param FilterTemplate $filterTemplate
     */
    public function __construct(
        Template\Context $context,
        ChartFactory $chartFactory,
        Config $config,
        Registry $coreRegistry,
        FilterProvider $filterProvider,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->chartFactory = $chartFactory;
        $this->config = $config;
        $this->coreRegistry = $coreRegistry;
        $this->filterProvider = $filterProvider;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        /** @var Product $product */
        $product = $this->coreRegistry->registry('current_product');
        return $product;
    }

    /**
     * Get the Size Chart
     *
     * @return ChartModel
     */
    public function getSizeChart()
    {
        /** @var Product $product */
        $product = $this->getProduct();
        /** @var ChartModel $chart */
        $chart = $this->getSizeChartByProduct($product);
        return $chart;
    }

    /**
     * Get the Size Chart by product
     *
     * @param $product
     * @return ChartModel
     */
    public function getSizeChartByProduct(Product $product)
    {
        /** @var ChartModel $chart */
        $chart = $this->chartFactory->create();
        $chart->getResource()->load($chart, $product->getData('size_chart'));
        return $chart;
    }

    /**
     * @param $content
     * @return string
     */
    public function filterContent($content)
    {
        $html = '';
        if ($content) {
            $storeId = $this->_storeManager->getStore()->getId();
            $html = $this->filterProvider->getBlockFilter()->setStoreId($storeId)->filter($content);
        }
        return $html;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->config->isEnabled()) {
            if (!$this->getTemplate()) {
                $this->setTemplate(self::TEMPLATE);
            }
            return parent::_toHtml();
        }
        return '';
    }
}