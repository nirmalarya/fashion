<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 06/09/17
 * Time: 1:59 AM
 */

namespace ScandicDesi\Catalog\Block\Category;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Data\Tree\Node\Collection as NodeCollection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use ScandicDesi\Catalog\Model\Config;

class CategoryList extends Template
{
    const MAX_SIZE = 10;
    const MIN_SIZE = 3;

    /** @var Collection */
    private $collection;

    /** @var Config */
    private $config;

    /** @var CategoryRepository */
    private $categoryRepository;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var Registry */
    private $coreRegister;

    /** @var null|Category */
    private $category = null;

    /**
     * CategoryList constructor.
     * @param Template\Context $context
     * @param CategoryRepository $categoryRepository
     * @param CollectionFactory $collectionFactory
     * @param Registry $coreRegister
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CategoryRepository $categoryRepository,
        CollectionFactory $collectionFactory,
        Registry $coreRegister,
        Config $config,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->collectionFactory = $collectionFactory;
        $this->coreRegister = $coreRegister;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        if ($this->category == null) {
            $this->category = $this->coreRegister->registry('current_category');
        }
        if ($this->category == null) {
            $storeRootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
            $this->category = $this->categoryRepository->get($storeRootCategoryId);
        }
        return $this->category;
    }

    /**
     * Get the list of categories by parent category id
     *
     * @return Collection|NodeCollection
     */
    public function getList()
    {
        if ($this->collection == null) {
            /** @var Collection $this ->collection */
            $this->collection = $this->collectionFactory->create();
            $this->collection->addAttributeToSelect('name');
            $this->collection->addAttributeToSelect('image');

            $categoryId = $this->config->getConfigValue('occasions_category_id');
            if ($categoryId) {
                $this->collection->addFieldToFilter(
                    'path',
                    ['like' => '%' . $categoryId . '/%']
                );
            } else {
                $storeId = $this->_storeManager->getStore()->getId();
                $this->collection->setStoreId($storeId);
                $this->collection
                    ->addFieldToFilter(
                        'path',
                        ['like' => '%' . $this->getCategory()->getId() . '/%']
                    );
            }
            $this->collection->addAttributeToFilter('image', ['neq' => '']);
            $this->collection->setPageSize(self::MAX_SIZE);
            $this->collection->addIsActiveFilter();
            $this->collection->addUrlRewriteToResult();
            $this->collection->addOrder('level', Collection::SORT_ORDER_ASC);
            $this->collection->addOrder('position', Collection::SORT_ORDER_ASC);
            $this->collection->addOrder('parent_id', Collection::SORT_ORDER_ASC);
            $this->collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);
        }
        return $this->collection;
    }

    /**
     * Check if module is enabled before preparing the HTML output
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->config->isEnabled()) {
            return parent::_toHtml();
        }
    }
}