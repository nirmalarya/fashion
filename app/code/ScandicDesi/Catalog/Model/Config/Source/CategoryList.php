<?php
/**
 * Created by PhpStorm.
 * User: vkranjith
 * Date: 07/09/17
 * Time: 10:44 PM
 */

namespace ScandicDesi\Catalog\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\Framework\Option\ArrayInterface;
use Magento\Store\Model\StoreManagerInterface;

class CategoryList implements ArrayInterface
{
    private $request;
    private $storeManager;
    /**
     * @var array
     */
    private $options;

    /**
     * @var Collection
     */
    private $categoryFactory;

    /**
     * @param CollectionFactory $categoryFactory
     * @param Request $request
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CollectionFactory $categoryFactory,
        Request $request,
        StoreManagerInterface $storeManager
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $storeRootCategoryId = $this->getStoreRootCategoryId();
            /** @var Collection $categories */
            $categories = $this->categoryFactory->create();
            $this->options = $categories->addNameToResult()
                ->addFieldToFilter('level', ['gteq' => '2'])
                ->addPathsFilter("1/{$storeRootCategoryId}")
                ->addOrderField('parent_id')
                ->addOrderField('position')
                ->load()
                ->toOptionArray();
        }
        return $this->options;
    }

    private function getStoreRootCategoryId()
    {
        $websiteId = $this->request->getParam('website', false);
        $storeId = $this->request->getParam('store', false);

        if (!$storeId && $websiteId) {
            $storeId = $this->storeManager->getWebsite($websiteId)->getDefaultGroupId();
        } elseif (!$storeId && !$websiteId) {
            $storeId = $this->storeManager->getWebsite(true)->getDefaultGroupId();
        }
        return $this->storeManager->getStore($storeId)->getRootCategoryId();
    }
}