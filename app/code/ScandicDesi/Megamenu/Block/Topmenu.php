<?php
/**
 * Created by PhpStorm.
 * User: Ranjith.VK
 * Date: 6/5/2017
 * Time: 12:58 PM
 */

namespace Echidna\Megamenu\Block;

use Magento\Cms\Block\Block;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Theme\Block\Html\Topmenu as MagentoTopmenu;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;

class Topmenu extends MagentoTopmenu
{
    /** @var CategoryFactory $categoryFactory */
    private $categoryFactory;

    /**
     * @param Template\Context $context
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        CategoryFactory $categoryFactory,
        array $data = []
    ) {
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Build the Echidna Menu html output
     * @param $category
     *
     * @return string
     */
    public function getEchidnaMenuHtml($category)
    {
        $columns = $category->getEchidnaMenuTemplate();
        $template = $this->getLayoutTemplate();
        /** @var Template $block */
        $menuBlock = $this->getLayout()
            ->createBlock(Template::class, 'echidna_menu_block' . $category->getId())
            ->setTemplate($template);
        $blockColumns = [];
		$mobileColumns = 0;
        for ($column = 1; $column <= $columns; $column++) {
			
            if ($category->getData('echidna_menu_col_type' . $column) == 1) { // Category
                /** @var Category $subCategory */
                $subCategory = $this->categoryFactory->create();
                $subCategory->getResource()
                    ->load(
                        $subCategory,
                        $category->getData('echidna_menu_col_category' . $column)
                    );
                /** @var Template $block */
                $block = $this->getLayout()
                    ->createBlock(Template::class)
                    ->setTemplate($this->getCategoryListTemplate())
                    ->setCategory($subCategory)
                    ->setCategoryList($subCategory->getChildrenCategories());
                $blockColumns[$column]['html'] = $block->toHtml();
				
				$hideInMbl_Value = $category->getData('echidna_menu_col_mobilehide' . $column);
                $blockColumns[$column]['hide_in_mbl'] = $hideInMbl_Value;
				$mobileColumns += $hideInMbl_Value?1:0;
            } elseif ($category->getData('echidna_menu_col_type' . $column) == 2) { // static block
                /** @var Block $block */
                $block = $this->getLayout()
                    ->createBlock(Block::class)
                    ->setBlockId($category->getData('echidna_menu_col_block' . $column));
                $blockColumns[$column]['html'] = $block->toHtml();
				
				$hideInMbl_Value = $category->getData('echidna_menu_col_mobilehide' . $column);
                $blockColumns[$column]['hide_in_mbl'] = $hideInMbl_Value;
				$mobileColumns += $hideInMbl_Value?1:0;
            } elseif ($category->getData('echidna_menu_col_type' . $column) == 3) { // Custom content
                /** @var Template $block */
                $block = $this->getLayout()
                    ->createBlock(Template::class)
                    ->setTemplate($this->getStaticContentTemplate())
                    ->setCategory($category)
                    ->setContentField($column);
                $blockColumns[$column]['html'] = $block->toHtml();
				
				$hideInMbl_Value = $category->getData('echidna_menu_col_mobilehide' . $column);
                $blockColumns[$column]['hide_in_mbl'] = $hideInMbl_Value;
				$mobileColumns += $hideInMbl_Value?1:0;
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
        $childId = $child->getId();
        preg_match_all('/[0-9]/', $childId, $match);
        $childId = implode('', $match[0]);
        /** @var Category $category */
        $category = $this->categoryFactory->create();
        $category->getResource()->load($category, $childId);
        $html = '';
        if ($category->getEchidnaMenuEnable()) {
            $html .= $this->getEchidnaMenuHtml($category);
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
