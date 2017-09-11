<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ScandicDesi\SizeChart\Model\Chart;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use ScandicDesi\SizeChart\Model\Chart;
use ScandicDesi\SizeChart\Model\ResourceModel\Chart\Collection;
use ScandicDesi\SizeChart\Model\ResourceModel\Chart\CollectionFactory;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var string
     */
    private $chartTable;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $chartCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $chartCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $chartCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var Chart $chart */
        foreach ($items as $chart) {
            $this->loadedData[$chart->getId()] = $chart->getData();
        }

        $data = $this->dataPersistor->get($this->chartTable);
        if (!empty($data)) {
            $chart = $this->collection->getNewEmptyItem();
            $chart->setData($data);
            $this->loadedData[$chart->getId()] = $chart->getData();
            $this->dataPersistor->clear($this->chartTable);
        }

        return $this->loadedData;
    }
}
