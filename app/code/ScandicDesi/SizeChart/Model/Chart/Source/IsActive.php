<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ScandicDesi\SizeChart\Model\Chart\Source;

use Magento\Framework\Data\OptionSourceInterface;
use ScandicDesi\SizeChart\Model\Chart;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var Chart
     */
    private $chart;

    /**
     * IsActive constructor.
     * @param Chart $chart
     */
    public function __construct(Chart $chart)
    {
        $this->chart = $chart;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->chart->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
