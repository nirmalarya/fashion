/**
 * This script is a simple wrapper that allows you to use OwlCarousel with Magento 2
 */

define([
    "jquery",
	"jquery/jquery-migrate",
    "Magento_Theme/owl-carousal2/js/owl.carousel.min"
], function($){	
	console.log('data-mage-init time : owlCarousel is a typeof -> '+(typeof jQuery.fn.owlCarousel));
	return function (config, element) {
		return $(element).owlCarousel(config);
	}	
});