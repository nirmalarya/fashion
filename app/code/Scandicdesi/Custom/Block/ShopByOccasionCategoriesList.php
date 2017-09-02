<?php

namespace Scandicdesi\Custom\Block;
use Magento\Catalog\Model\CategoryRepositoryFactory;
use Magento\Store\Model\StoreManager;

class ShopByOccasionCategoriesList extends \Magento\Framework\View\Element\Template{
		
	const MIN_CAT_TO_DISPLAY = 3;
	/** @var CategoryRepository */
	protected $_categoryRepository;
	
	/** @var StoreManager */
	protected $_storeManager;
	
    protected $_categoryHelper;
    
    /** @var \Magento\Framework\Registry */
    protected $_registry;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
		CategoryRepositoryFactory $categoryRepository,
        \Magento\Framework\Registry $registry,
		StoreManager $storeManager,
        array $data = []
    )
    {      
        $this->_categoryHelper = $categoryHelper;
		$this->_categoryRepository = $categoryRepository;
		$this->_storeManager = $storeManager;
        $this->_registry = $registry;
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
	
    /**
	 * Return the current category object
	 *
	 * @return \Magento\Catalog\Model\Category | null
	 */
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }
    
    /**
	 * Return the current store categories | current category sub-categories (Array)
	 *
	 * @return \Magento\Catalog\Model\Category | null
	 */
    public function getCategoriesList()
    {
        $categoryArrayList = [];
        
        $categoriesList =   $this->getStoreCategories();
        if($currentCategory = $this->getCurrentCategory()){
            $categoriesList =  $currentCategory->getChildrenCategories();
        }
       
        $i = 0 ;
        foreach($categoriesList as $item){
            if($item->getIsActive()){            
                $getCategoryObj = $this->getCategory($item); 
                $getImageUrl = $getCategoryObj->getImageUrl();
              
                if($getImageUrl){
                    $categoryArrayList[$i]['name'] = $getCategoryObj->getName();
                    $categoryArrayList[$i]['url'] = $getCategoryObj->getUrl();
                    $categoryArrayList[$i]['image_url'] = $getImageUrl;
                    $i++;
                }
            }
        }
        
        if($i >= self::MIN_CAT_TO_DISPLAY){
            return $categoryArrayList;
        }
        return [];
    }
    
    
	
}