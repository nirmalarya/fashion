<?php
/**
 * Created by PhpStorm.
 * User: Ranjith.VK
 * Date: 6/5/2017
 * Time: 12:58 PM
 */

namespace ScandicDesi\Megamenu\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Cms\Block\Block;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Theme\Block\Html\Topmenu as MagentoTopmenu;
use ScandicDesi\Megamenu\Model\Config;

class Topmenu extends MagentoTopmenu
{
    /** @var Config */
    private $config;

    /** @var Collection|null */
    private $categories = null;

    /** @var CategoryFactory */
    private $categoryFactory;

    /**
     * Topmenu constructor.
     * @param Template\Context $context
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     * @param CategoryFactory $categoryFactory
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        CategoryFactory $categoryFactory,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
        $this->categoryFactory = $categoryFactory;
        $this->config = $config;
    }

    /**
     * @return Collection|Node\Collection
     */
    private function getCategories()
    {
        if ($this->categories == null) {
            $storeRootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
            $this->categories = $this->categoryFactory->create()
                ->getCategories($storeRootCategoryId, 5, true, true);
        }
        return $this->categories;
    }

    /**
     * @param Node $child
     * @param $column
     * @return string
     */
    private function getCategoryContent(Node $child, $column)
    {
        $parentId = $child->getData('megamenu_col_category' . $column);
        
        /** @var string $category */
        $parentCategory = null;
        
        /** @var array $categories */
        $categories = [];
        foreach ($this->getCategories() as $category) {
             
            if($parentId == $category->getId()) {
                $parentCategory = $category;                
            }
            
            /** @var Category $category */
            if ($parentId == $category->getParentId()) {
                $categories[] = $category;
            }
        }
        
        /** @var Template $block */
        $block = $this->getLayout()
            ->createBlock(Template::class)
            ->setTemplate($this->getCategoryListTemplate())
            ->setCategoryList($categories)
            ->setParentCategory($parentCategory);

        return $block->toHtml();
    }

    /**
     * @param Node $child
     * @param $column
     * @return string
     */
    private function getBlockContent(Node $child, $column)
    {
        /** @var Block $block */
        $block = $this->getLayout()
            ->createBlock(Block::class)
            ->setBlockId($child->getData('megamenu_col_block' . $column));
        return $block->toHtml();
    }

    /**
     * @param Node $child
     * @param $column
     * @return string
     */
    private function getCustomContent(Node $child, $column)
    {
        /** @var Template $block */
        $block = $this->getLayout()
            ->createBlock(Template::class)
            ->setTemplate($this->getStaticContentTemplate())
            ->setCategory($child)
            ->setContentField($column);
        return $block->toHtml();
    }

    /**
     * Build the ScandicDesi Menu html output
     *
     * @param Node $child
     * @return string|bool
     */
    public function getMegamenuHtml(Node $child)
    {
        if (!$this->config->isEnabled()) {
            return false;
        }

        $columns = (int) $child->getData('megamenu_template');
        $template = $this->getLayoutTemplate();
        /** @var Template $block */
        $menuBlock = $this->getLayout()
            ->createBlock(Template::class, 'megamenu_block' . $child->getId())
            ->setTemplate($template);
        $blockColumns = [];
        $mobileColumns = 0;
        if ($columns) {
            for ($column = 1; $column <= $columns; $column++) {
                if ($child->getData('megamenu_col_type' . $column) == 1) { // Category
                    $blockColumns[$column]['html'] = $this->getCategoryContent($child, $column);

                    $hideInMbl_Value = $child->getData('megamenu_col_mobilehide' . $column);
                    $blockColumns[$column]['hide_in_mbl'] = $hideInMbl_Value;
                    $blockColumns[$column]['type_of_block'] = "category-block";
                    $mobileColumns += $hideInMbl_Value ? 1 : 0;
                } elseif ($child->getData('megamenu_col_type' . $column) == 2) { // static block
                    $blockColumns[$column]['html'] = $this->getBlockContent($child, $column);

                    $hideInMbl_Value = $child->getData('megamenu_col_mobilehide' . $column);
                    $blockColumns[$column]['hide_in_mbl'] = $hideInMbl_Value;
                    $blockColumns[$column]['type_of_block'] = "static-block";
                    $mobileColumns += $hideInMbl_Value ? 1 : 0;
                } elseif ($child->getData('megamenu_col_type' . $column) == 3) { // Custom content
                    $blockColumns[$column]['html'] = $this->getCustomContent($child, $column);

                    $hideInMbl_Value = $child->getData('megamenu_col_mobilehide' . $column);
                    $blockColumns[$column]['hide_in_mbl'] = $hideInMbl_Value;
                    $blockColumns[$column]['type_of_block'] = "custom-content-block";
                    $mobileColumns += $hideInMbl_Value ? 1 : 0;
                }
            }
        }
        $blockColumns['enable_mobile_columns'] = $mobileColumns;
        return $menuBlock->setColumns($blockColumns)->toHtml();
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
        if ($child->getData('megamenu_enable')) {
            $html .= $this->getMegamenuHtml($child);
        } else {
            if (!$child->hasChildren()) {
                return $html;
            }

            $colStops = null;
            if ($childLevel == 0 && $limit) {
                $colStops = $this->_columnBrake($child->getChildren(), $limit);
            }

            $html .= '<ul class="level' . $childLevel . ' submenu">';
            $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
            $html .= '</ul>';
        }

        return $html;
    }
}
