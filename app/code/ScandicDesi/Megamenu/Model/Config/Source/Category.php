<?php
/**
 * Created by PhpStorm.
 * User: Ranjith.VK
 * Date: 6/4/2017
 * Time: 9:09 PM
 */

namespace ScandicDesi\Megamenu\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Source model for ScandicDesi Megamenu category
 */
class Category extends AbstractSource
{
    /** @var CategoryCollection $categoryCollectionFactory */
    private $categoryCollectionFactory;

    public function __construct(CategoryCollectionFactory $categoryCollectionFactory)
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->toOptionArray(true);
        }
        return $this->_options;
    }

    /**
     * Return option array
     *
     * @param bool $addEmpty
     * @return array
     */
    public function toOptionArray($addEmpty = true)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->categoryCollectionFactory->create();

        $collection->addAttributeToSelect('name')
            ->addFieldToFilter('path', ['neq' => '1'])
            ->addIsActiveFilter()
            ->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('Please select a category.'), 'value' => ''];
        }
        foreach ($collection as $category) {
            $options[] = ['label' => $category->getName(), 'value' => $category->getId()];
        }

        return $options;
    }
}
