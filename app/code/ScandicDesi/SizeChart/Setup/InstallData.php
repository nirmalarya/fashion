<?php
/**
 * Created by PhpStorm.
 * User: Ranjith.VK
 * Date: 6/4/2017
 * Time: 1:47 PM
 */

namespace ScandicDesi\SizeChart\Setup;

use Magento\Catalog\Model\Product;
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
            'size_chart' => [
                'type' => 'int',
                'label' => 'Size Chart',
                'input' => 'select',
                'source' => 'ScandicDesi\SizeChart\Model\Attribute\Source\SizeChart',
                'default' => '',
                'sort_order' => 100,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
                'required' => false
            ]
        ];

        foreach ($categoryAttributes as $attributeCode => $attributeValue) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $attributeCode,
                $attributeValue
            );
        }

        $setup->endSetup();
    }
}
