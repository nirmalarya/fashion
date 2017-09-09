<?php
/**
 * Created by PhpStorm.
 * User: Ranjith.VK
 * Date: 6/4/2017
 * Time: 1:47 PM
 */

namespace ScandicDesi\Catalog\Setup;

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
            'category_type' => [
                'type' => 'varchar',
                'label' => 'Category Type',
                'input' => 'select',
                'source' => 'ScandicDesi\Catalog\Model\Attribute\Source\Category\Type',
                'default' => '',
                'sort_order' => 10,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'general',
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
