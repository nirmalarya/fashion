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

class CategoryList extends Template
{
    const MAX_SIZE = 10;
    const MIN_SIZE = 3;

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
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CategoryRepository $categoryRepository,
        CollectionFactory $collectionFactory,
        Registry $coreRegister,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->collectionFactory = $collectionFactory;
        $this->coreRegister = $coreRegister;
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
        $storeId = $this->_storeManager->getStore()->getId();
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('image');
        $collection->addFieldToFilter('path', ['like' => '%' . $this->getCategory()->getId() . '/%']);
        $collection->addAttributeToFilter('image', ['neq' => '']);
        $collection->setPageSize(self::MAX_SIZE);
        $collection->addIsActiveFilter();
        $collection->addUrlRewriteToResult();
        $collection->addOrder('level', Collection::SORT_ORDER_ASC);
        $collection->addOrder('position', Collection::SORT_ORDER_ASC);
        $collection->addOrder('parent_id', Collection::SORT_ORDER_ASC);
        $collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);
        return $collection;
    }
}