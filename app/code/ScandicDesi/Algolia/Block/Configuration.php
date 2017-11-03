<?php

namespace ScandicDesi\Algolia\Block;

use Magento\Framework\Data\CollectionDataSourceInterface;
use Magento\Framework\DataObject;

use Algolia\AlgoliaSearch\Helper\AlgoliaHelper;
use Algolia\AlgoliaSearch\Helper\ConfigHelper;
use Algolia\AlgoliaSearch\Helper\Entity\ProductHelper;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Locale\Currency;
use Magento\Framework\Registry;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Search\Helper\Data as CatalogSearchHelper;
use Magento\Catalog\Model\CategoryRepositoryFactory;

class Configuration extends \Algolia\AlgoliaSearch\Block\Configuration implements CollectionDataSourceInterface
{
    /**
     * @var WeltPixel\Quickview\Plugin\BlockProductList --  for custom button 
     */
	const XML_PATH_QUICKVIEW_ENABLED = 'weltpixel_quickview/general/enable_product_listing';
    const XML_PATH_QUICKVIEW_BUTTONSTYLE = 'weltpixel_quickview/general/button_style';
	/**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
     
    protected $urlHelper;
    
    
     public function __construct(
        Template\Context $context,
        ConfigHelper $config,
        CatalogSearchHelper $catalogSearchHelper,
        Session $customerSession,
        ProductHelper $productHelper,
        Currency $currency,
        Registry $registry,
        AlgoliaHelper $algoliaHelper,
        Data $urlHelper,
        FormKey $formKey,	
		CategoryRepositoryFactory $categoryRepository,
		\Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        array $data = []
    ) {
        $this->urlHelper = $urlHelper;
		$this->_scopeConfig = $context->getScopeConfig();

        parent::__construct(
			$context,
			$config,
			$catalogSearchHelper,
			$customerSession,
			$productHelper,
			$currency,
			$registry,
			$algoliaHelper,
			$urlHelper,
			$formKey,
			$data);
    }
    
    
    
   

    public function getConfiguration()
    {
        $config = $this->getConfigHelper();

        $catalogSearchHelper = $this->getCatalogSearchHelper();

        $productHelper = $this->getProductHelper();

        $algoliaHelper = $this->getAlgoliaHelper();

        $baseUrl = rtrim($this->getBaseUrl(), '/');

        $isCategoryPage = false;

        $currencyCode = $this->getCurrencyCode();
        $currencySymbol = $this->getCurrencySymbol();

        $customerGroupId = $this->getGroupId();

        $priceKey = $this->getPriceKey();

        $query = '';
        $refinementKey = '';
        $refinementValue = '';
        $path = '';
        $level = '';

        $addToCartParams = $this->getAddToCartParams();

        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();

        /**
         * Handle category replacement
         */
        if ($config->isInstantEnabled() && $config->replaceCategories() && $request->getControllerName() == 'category') {
            $category = $this->getCurrentCategory();

            if ($category && $category->getDisplayMode() !== 'PAGE') {
                $category->getUrlInstance()->setStore($this->getStoreId());

                $level = -1;
                foreach ($category->getPathIds() as $treeCategoryId) {
                    if ($path != '') {
                        $path .= ' /// ';
                    }

                    $path .= $productHelper->getCategoryName($treeCategoryId, $this->getStoreId());

                    if ($path) {
                        $level++;
                    }
                }

                $isCategoryPage = true;
            }
        }

        /**
         * Handle search
         */
        $facets = $config->getFacets();

        $areCategoriesInFacets = false;

        if ($config->isInstantEnabled()) {
            $pageIdentifier = $request->getFullActionName();

            if ($pageIdentifier === 'catalogsearch_result_index') {
                $query = $this->getRequest()->getParam($catalogSearchHelper->getQueryParamName());

                if ($query == '__empty__') {
                    $query = '';
                }

                $refinementKey = $this->getRequest()->getParam('refinement_key');

                if ($refinementKey !== null) {
                    $refinementValue = $query;
                    $query = "";
                }
                else {
                    $refinementKey = "";
                }
            }

            foreach ($facets as $facet) {
                if ($facet['attribute'] === 'categories') {
                    $areCategoriesInFacets = true;
                    break;
                }
            }
        }

        $algoliaJsConfig = [
            'instant' => [
                'enabled' => (bool) $config->isInstantEnabled(),
                'selector' => $config->getInstantSelector(),
                'isAddToCartEnabled' => $config->isAddToCartEnable(),
                'addToCartParams' => $addToCartParams,
                'extraRequiredParams' => $this->getExtraRequiredParams(),
            ],
            'autocomplete' => [
                'enabled' => (bool) $config->isAutoCompleteEnabled(),
                'selector' => $config->getAutocompleteSelector(),
                'sections' => $config->getAutocompleteSections(),
                'nbOfProductsSuggestions' => $config->getNumberOfProductsSuggestions(),
                'nbOfCategoriesSuggestions' => $config->getNumberOfCategoriesSuggestions(),
                'nbOfQueriesSuggestions' => $config->getNumberOfQueriesSuggestions(),
            ],
            'extensionVersion' => $config->getExtensionVersion(),
            'applicationId' => $config->getApplicationID(),
            'indexName' => $productHelper->getBaseIndexName(),
            'apiKey' => $algoliaHelper->generateSearchSecuredApiKey($config->getSearchOnlyAPIKey(), $config->getAttributesToRetrieve($customerGroupId)),
            'facets' => $facets,
            'areCategoriesInFacets' => $areCategoriesInFacets,
            'hitsPerPage' => (int) $config->getNumberOfProductResults(),
            'sortingIndices' => array_values($config->getSortingIndices()),
            'isSearchPage' => $this->isSearchPage(),
            'isCategoryPage' => $isCategoryPage,
            'removeBranding' => (bool) $config->isRemoveBranding(),
            'priceKey' => $priceKey,
            'currencyCode' => $currencyCode,
            'currencySymbol' => $currencySymbol,
            'maxValuesPerFacet' => (int) $config->getMaxValuesPerFacet(),
            'autofocus' => true,
            'request' => [
                'query' => html_entity_decode($query),
                'refinementKey' => $refinementKey,
                'refinementValue' => $refinementValue,
                'path' => $path,
                'level' => $level,
            ],
            'showCatsNotIncludedInNavigation' => $config->showCatsNotIncludedInNavigation(),
            'showSuggestionsOnNoResultsPage' => $config->showSuggestionsOnNoResultsPage(),
            'baseUrl' => $baseUrl,
            'popularQueries' => $config->getPopularQueries(),
            'urls' => [
                'logo' => $this->getViewFileUrl('Algolia_AlgoliaSearch::images/search-by-algolia.svg'),
            ],
            'analytics' => $config->getAnalyticsConfig(),
            'translations' => [
                'to' => __('to'),
                'or' => __('or'),
                'go' => __('Go'),
                'popularQueries' => __('You can try one of the popular search queries'),
                'seeAll' => __('See all products'),
                'allDepartments' => __('All departments'),
                'seeIn' => __('See products in'),
                'orIn' => __('or in'),
                'noProducts' => __('No products for query'),
                'noResults' => __('No results'),
                'refine' => __('Refine'),
                'selectedFilters' => __('Selected Filters'),
                'clearAll' => __('Clear all'),
                'previousPage' => __('Previous page'),
                'nextPage' => __('Next page'),
                'searchFor' => __('Search for products'),
                'relevance' => __('Relevance'),
                'categories' => __('Categories'),
                'products' => __('Products'),
                'searchBy' => __('Search by'),
            ],
        ];

        $transport = new DataObject($algoliaJsConfig);
        $this->_eventManager->dispatch('algolia_after_create_configuration', ['configuration' => $transport]);
        $algoliaJsConfig = $transport->getData();

        return $algoliaJsConfig;
    }
    
    public function getExtraRequiredParams(){		
		$getCurrentUrl = $this->urlHelper->getEncodedUrl($this->_urlBuilder->getCurrentUrl());
		$compareUrl =  $this->_urlBuilder->getUrl('catalog/product_compare/add');    
        $wishListUrl =  $this->_urlBuilder->getUrl('wishlist/index/add');
        $quickViewUrl =  $this->_urlBuilder->getUrl('weltpixel_quickview/catalog_product/view');
		
		
		$isQuickViewEnabled = $this->_scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        	 
		$quickViewButtonStyle = $this->_scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONSTYLE,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE); 
		
        return [
            'currentUrl_Encode' => $getCurrentUrl,
            'compareUrl' => $compareUrl,
            'wishListUrl' => $wishListUrl,
            'quickViewUrl' => $quickViewUrl,
            'quickViewButtonStyle' => $quickViewButtonStyle,
            'isQuickViewEnable' => $isQuickViewEnabled,
        ];
    }
}
