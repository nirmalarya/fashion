<?php
/**
 * Created by PhpStorm.
 * User: Ranjith.VK
 * Date: 6/4/2017
 * Time: 9:09 PM
 */

namespace ScandicDesi\SizeChart\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use ScandicDesi\SizeChart\Model\ResourceModel\Chart\Collection;
use ScandicDesi\SizeChart\Model\ResourceModel\Chart\CollectionFactory;

/**
 * Source model for ScandicDesi Megamenu category
 */
class SizeChart extends AbstractSource
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * SizeChart constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
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
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        $collection->addFieldToFilter('is_active', ['eq' => '1'])
            ->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('Please select a size chart'), 'value' => ''];
        }
        foreach ($collection as $item) {
            $options[] = ['label' => $item->getTitle(), 'value' => $item->getId()];
        }

        return $options;
    }
}
