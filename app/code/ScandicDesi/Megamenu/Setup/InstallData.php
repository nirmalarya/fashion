<?php
/**
 * Created by PhpStorm.
 * User: Ranjith.VK
 * Date: 6/4/2017
 * Time: 1:47 PM
 */

namespace ScandicDesi\Megamenu\Setup;

use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;

class InstallData implements InstallDataInterface
{
    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $categoryAttributes = [
            'megamenu_enable' => [
                'type' => 'int',
                'label' => 'Enable Mega Menu',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'default' => '0',
                'sort_order' => 10,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_template' => [
                'type' => 'int',
                'label' => 'Mega Menu Template',
                'input' => 'select',
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Template',
                'default' => '1',
                'sort_order' => 20,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_type1' => [
                'type' => 'int',
                'label' => 'Column 1 Type',
                'input' => 'select',
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Template\Type',
                'default' => '1',
                'sort_order' => 30,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_block1' => [
                'type' => 'int',
                'label' => 'Column 1 Content',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'default' => '1',
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_content1' => [
                'type' => 'text',
                'label' => 'Column 1 Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Menu',
            ],
            'megamenu_col_category1' => [
                'type' => 'varchar',
                'label' => 'Column 1 Content',
                'input' => 'text',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Category',
                'group' => 'Menu',
            ],
            'megamenu_col_type2' => [
                'type' => 'int',
                'label' => 'Column 2 Type',
                'input' => 'select',
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Template\Type',
                'default' => '1',
                'sort_order' => 30,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_block2' => [
                'type' => 'int',
                'label' => 'Column 2 Content',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'default' => '1',
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_content2' => [
                'type' => 'text',
                'label' => 'Column 2 Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Menu',
            ],
            'megamenu_col_category2' => [
                'type' => 'varchar',
                'label' => 'Column 2 Content',
                'input' => 'text',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Category',
                'group' => 'Menu',
            ],
            'megamenu_col_type3' => [
                'type' => 'int',
                'label' => 'Column 3 Type',
                'input' => 'select',
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Template\Type',
                'default' => '1',
                'sort_order' => 30,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_block3' => [
                'type' => 'int',
                'label' => 'Column 3 Content',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'default' => '1',
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_content3' => [
                'type' => 'text',
                'label' => 'Column 3 Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Menu',
            ],
            'megamenu_col_category3' => [
                'type' => 'varchar',
                'label' => 'Column 3 Content',
                'input' => 'text',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Category',
                'group' => 'Menu',
            ],
            'megamenu_col_type4' => [
                'type' => 'int',
                'label' => 'Column 4 Type',
                'input' => 'select',
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Template\Type',
                'default' => '1',
                'sort_order' => 30,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_block4' => [
                'type' => 'int',
                'label' => 'Column 4 Content',
                'input' => 'select',
                'source' => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                'default' => '1',
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Menu',
            ],
            'megamenu_col_content4' => [
                'type' => 'text',
                'label' => 'Column 4 Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'Menu',
            ],
            'megamenu_col_category4' => [
                'type' => 'varchar',
                'label' => 'Column 4 Content',
                'input' => 'text',
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'source' => 'ScandicDesi\Megamenu\Model\Config\Source\Category',
                'group' => 'Menu',
            ]
        ];

        foreach ($categoryAttributes as $attributeCode => $attributeValue) {
            $eavSetup->addAttribute(
                Category::ENTITY,
                $attributeCode,
                $attributeValue
            );
        }

        $setup->endSetup();
    }
}
