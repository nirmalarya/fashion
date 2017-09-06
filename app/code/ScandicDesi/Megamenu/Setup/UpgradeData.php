<?php
/**
 * Created by PhpStorm.
 * User: Kanhaiya Lal
 * Date: 14/07/2017
 * Time: 8:00 PM
 */

namespace ScandicDesi\Megamenu\Setup;

use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;


class UpgradeData implements UpgradeDataInterface
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

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<=')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $categoryAttributes = [
                'megamenu_col_mobilehide1' => [
                    'type' => 'int',
                    'label' => 'Column 1 Hide in Mobile',
                    'input' => 'select',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'default' => '0',
                    'sort_order' => 10,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Menu',
                ],
                'megamenu_col_mobilehide2' => [
                    'type' => 'int',
                    'label' => 'Column 2 Hide in Mobile',
                    'input' => 'select',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'default' => '0',
                    'sort_order' => 10,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Menu',
                ],
                'megamenu_col_mobilehide3' => [
                    'type' => 'int',
                    'label' => 'Column 3 Hide in Mobile',
                    'input' => 'select',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'default' => '0',
                    'sort_order' => 10,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Menu',
                ],
                'megamenu_col_mobilehide4' => [
                    'type' => 'int',
                    'label' => 'Column 4 Hide in Mobile',
                    'input' => 'select',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'default' => '0',
                    'sort_order' => 10,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Menu',
                ],
            ];

            foreach ($categoryAttributes as $attributeCode => $attributeValue) {
                $eavSetup->addAttribute(
                    Category::ENTITY,
                    $attributeCode,
                    $attributeValue
                );
            }
        }

        $setup->endSetup();
    }
}
