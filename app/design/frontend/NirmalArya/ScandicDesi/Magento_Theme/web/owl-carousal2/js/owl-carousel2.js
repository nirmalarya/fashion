/**
 * This script is a simple wrapper that allows you to use OwlCarousel with Magento 2
 */

define([
    "jquery",
	"jquery/jquery-migrate",
    "Magento_Theme/owl-carousal2/js/owl.carousel.min"
], function($){	
    function checkWidths(event){
            var element   = event.target; 
            var activeItemIndex      = event.item.index;  

            var totalCnt = $(element).find('.owl-item').length;
            $(element).find('.owl-item').removeClass('prev-of-active').removeClass('next-of-active');
            
            
            var activeEle = $(element).find('.owl-item').eq(activeItemIndex);
            activeEle.prev().removeClass('next-of-active').addClass('prev-of-active');
            activeEle.next().removeClass('prev-of-active').addClass('next-of-active');
                    
            
            
            
            
	}
	console.log('data-mage-init time : owlCarousel is a typeof -> '+(typeof jQuery.fn.owlCarousel));
	return function (config, element) {
        
        config['onInitialized']= checkWidths;
		config['onChanged']= checkWidths;
		return $(element).owlCarousel(config);
	}	
});