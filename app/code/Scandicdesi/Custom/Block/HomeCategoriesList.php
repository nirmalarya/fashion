<?php

namespace Scandicdesi\Custom\Block;
use Magento\Catalog\Model\CategoryRepositoryFactory;
use Magento\Store\Model\StoreManager;

class HomeCategoriesList extends \Magento\Framework\View\Element\Template{
		
	
	/** @var CategoryRepository */
	protected $_categoryRepository;
	
	/** @var StoreManager */
	protected $_storeManager;
	
    protected $_categoryHelper;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
		CategoryRepositoryFactory $categoryRepository,
		StoreManager $storeManager,
        array $data = []
    )
    {      
        $this->_categoryHelper = $categoryHelper;
		$this->_categoryRepository = $categoryRepository;
		$this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }
	
	 /**
     * Retrieve current store categories
     *
     * @param bool|string $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @return \Magento\Framework\Data\Tree\Node\Collection or
     * \Magento\Catalog\Model\ResourceModel\Category\Collection or array
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted = false, $asCollection = false, $toLoad = true);
    }
	
	/**
	 * Return the category object
	 *
	 * @return \Magento\Catalog\Model\Category | null
	 */
	public function getCategory($category)
	{	
		$categoryObj =null;		
		if(($categoryId = $category->getId())) {				
			$categoryObj = $this->_categoryRepository->create()->get($categoryId, $this->_storeManager->getStore()->getStoreId());
		} 
		return $categoryObj;
	}
	
	
}