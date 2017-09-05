<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 06/09/17
 * Time: 1:59 AM
 */

namespace ScandicDesi\Catalog\Block\Category;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Data\Tree\Node\Collection as NodeCollection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class CategoryList extends Template
{
    private $categoryFactory;
    private $coreRegister;

    /** @var null|Category */
    private $category = null;

    public function __construct(
        Template\Context $context,
        CategoryFactory $categoryFactory,
        Registry $coreRegister,
        array $data = []
    ) {
        $this->categoryFactory = $categoryFactory;
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
            $this->category = $this->categoryFactory->create();
            $this->category->getResource()->load($this->category, 'entity_id', $storeRootCategoryId);
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
        $collection = $this->category->getCategories($this->category->getId(), 2, true, true);
        $collection->addIsActiveFilter()
            ->load();
        return $collection;
    }
}