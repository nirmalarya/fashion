<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 04/09/17
 * Time: 9:09 AM
 */

namespace ScandicDesi\Megamenu\Plugin\Catalog\Block;

use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use ScandicDesi\Megamenu\Model\Config;

class Topmenu
{
    /** @var NodeFactory */
    private $nodeFactory;

    /** @var Config */
    private $config;

    /**
     * Catalog category
     *
     * @var CategoryHelper
     */
    private $catalogCategory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Resolver
     */
    private $layerResolver;

    /**
     * Topmenu constructor.
     *
     * @param CategoryHelper $catalogCategory
     * @param CollectionFactory $categoryCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Resolver $layerResolver
     * @param NodeFactory $nodeFactory
     * @param Config $config
     */
    public function __construct(
        CategoryHelper $catalogCategory,
        CollectionFactory $categoryCollectionFactory,
        StoreManagerInterface $storeManager,
        Resolver $layerResolver,
        NodeFactory $nodeFactory,
        Config $config
    ) {
        $this->catalogCategory = $catalogCategory;
        $this->collectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        $this->layerResolver = $layerResolver;
        $this->nodeFactory = $nodeFactory;
        $this->config = $config;
    }

    /**
     * Build category tree for menu block.
     *
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return void
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        if ($this->config->isEnabled()) {
            $rootId = $this->storeManager->getStore()->getRootCategoryId();
            $storeId = $this->storeManager->getStore()->getId();
            /** @var CategoryCollection $collection */
            $collection = $this->getCategoryTree($storeId, $rootId);
            $currentCategory = $this->getCurrentCategory();
            $mapping = [$rootId => $subject->getMenu()];  // use nodes stack to avoid recursion
            $this->prepareCategoryNode($collection, $mapping, $currentCategory);
        }
    }

    /**
     * Get current Category from catalog layer
     *
     * @return Category
     */
    private function getCurrentCategory()
    {
        $catalogLayer = $this->layerResolver->get();

        if (!$catalogLayer) {
            return null;
        }

        return $catalogLayer->getCurrentCategory();
    }

    /**
     * Get Category Tree
     *
     * @param int $storeId
     * @param int $rootId
     * @return CategoryCollection
     * @throws LocalizedException
     */
    private function getCategoryTree($storeId, $rootId)
    {
        /** @var CategoryCollection $collection */
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect('name');
        $collection->addFieldToFilter('path', ['like' => '1/' . $rootId . '/%']); //load only from store root
        $collection->addAttributeToFilter('include_in_menu', 1);
        $collection->addIsActiveFilter();
        $collection->addUrlRewriteToResult();
        $collection->addOrder('level', Collection::SORT_ORDER_ASC);
        $collection->addOrder('position', Collection::SORT_ORDER_ASC);
        $collection->addOrder('parent_id', Collection::SORT_ORDER_ASC);
        $collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);

        $customAttributes = $this->getCustomAttributes();
        foreach ($customAttributes as $attribute) {
            $collection->addAttributeToSelect($attribute);
        }

        return $collection;
    }

    /**
     * Convert category to array
     *
     * @param Category $category
     * @param Category $currentCategory
     * @return array
     */
    private function getCategoryAsArray($category, $currentCategory)
    {
        $attributes = [
            'name' => $category->getName(),
            'id' => 'category-node-' . $category->getId(),
            'url' => $this->catalogCategory->getCategoryUrl($category),
            'has_active' => in_array((string)$category->getId(), explode('/', $currentCategory->getPath()), true),
            'is_active' => $category->getId() == $currentCategory->getId()
        ];

        $customAttributes = $this->getCustomAttributes();
        foreach ($customAttributes as $attribute) {
            $data = [
                $attribute => $category->getData($attribute)
            ];

            $attributes = array_merge($attributes, $data);
        }

        return $attributes;
    }

    /**
     * Return array of category custom attributes
     *
     * @return array
     */
    public function getCustomAttributes()
    {
        $attributes = [
            'megamenu_enable',
            'megamenu_template'
        ];

        $count = 4;
        for ($i = 1; $i <= $count; $i++) {
            $data = [
                'megamenu_col_type' . $i,
                'megamenu_col_mobilehide' . $i,
                'megamenu_col_category' . $i,
                'megamenu_col_block' . $i,
                'megamenu_col_content' . $i
            ];

            $attributes = array_merge($attributes, $data);
        }

        return $attributes;
    }

    /**
     * @param $collection
     * @param $mapping
     * @param $currentCategory
     */
    private function prepareCategoryNode($collection, $mapping, $currentCategory)
    {
        foreach ($collection as $category) {
            if (!isset($mapping[$category->getParentId()])) {
                continue;
            }
            /** @var Node $parentCategoryNode */
            $parentCategoryNode = $mapping[$category->getParentId()];

            $categoryNode = $this->nodeFactory->create([
                'data' => $this->getCategoryAsArray($category, $currentCategory),
                'idField' => 'id',
                'tree' => $parentCategoryNode->getTree(),
                'parent' => $parentCategoryNode
            ]);

            $parentCategoryNode->addChild($categoryNode);

            $mapping[$category->getId()] = $categoryNode; //add node in stack
        }
    }
}