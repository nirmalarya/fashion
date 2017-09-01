/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'mage/smart-keyboard-handler',
    'mage/mage',
    'mage/ie-class-fixer',
    'domReady!'
], function ($, keyboardHandler) {
    'use strict';

    if ($('body').hasClass('checkout-cart-index')) {
        if ($('#co-shipping-method-form .fieldset.rates').length > 0 && $('#co-shipping-method-form .fieldset.rates :checked').length === 0) {
            $('#block-shipping').on('collapsiblecreate', function () {
                $('#block-shipping').collapsible('forceActivate');
            });
        }
    }

    $('.cart-summary').mage('sticky', {
        container: '#maincontent'
    });

    //$('.panel.header > .header.links').clone().appendTo('#store\\.links');
    $('.header.content > .account_li.wrapper > .header.links').clone().appendTo('#store\\.links');

    keyboardHandler.apply();
	
	
	
	/* floating header */
    var floatHeader = function(container, header, nav, nav_depand_ele) { 
        var containerFixed = $(container+'-fixed');
        var container = $(container);
        var header = $(header);
		
		var nav = $(nav);
		var navDepandEle_Obj =  $(nav_depand_ele);
		var topmenu_Obj =  $('#ms-topmenu');
		
        var headerPanel = $('.panel.wrapper');
        var search = $('.block-search');
        var searchWidth = search.outerWidth();
		var searchHeight = search.outerHeight(true);        
        if(!containerFixed.hasClass('onload-fixed')){
			containerFixed.addClass('onload-fixed')
		}
        var containerFixedH = containerFixed.outerHeight();
		/* if(!nav.is(':visible')){
			containerFixedH += nav.height();
		} */
		
		container.height(containerFixedH);
		container.css('min-height',containerFixedH);
		
		if(nav.attr('style') != undefined){ //reset style for nav
			nav.removeAttr('style');				
		}
		
		var _top = $(window).scrollTop();		
		var _direction;
		$(window).off('scroll.floatHeader');
        $(window).on('scroll.floatHeader',function (event) {
			var _cur_top = $(window).scrollTop();
			
            var scroll = $(window).scrollTop();
			var windowWidth =  $(window).width();
			
			
			if(_top<0){ // ipad/iphone top touch scroll problem getting (-) values 
				_top=0;
			}
			if(_top < _cur_top){
				_direction = 'down';
				
			}else{				
				_direction = 'up';	
			}
			_top = _cur_top;
			if(scroll > 50) {
				 startFloating(true);
				containerFixed.stop(0).addClass('fixed');
            } else {
				 startFloating(false);
				containerFixed.stop(0).removeClass('fixed');
            }
        });
		
		var startFloating = function(start) {
            if(start) {
                toggleMenu(start);
            } else {
                toggleMenu(false);
            }
        }
		
		var toggleMenu = function(hide) {
			var topmenu_ActiveLis = topmenu_Obj.find(' > li.active').length;
			if(hide && !navDepandEle_Obj.hasClass('open') && !topmenu_ActiveLis) {
				nav.stop(0).slideUp();
				navDepandEle_Obj.removeClass('open');
			} else {
				if(topmenu_ActiveLis){
					navDepandEle_Obj.addClass('open');
				}
				nav.stop(0).slideDown();
			}
			
        }
        
    }
	
    /* call floating header */
    floatHeader('#header-nav-container', '.page-header', '.nav-sections','#web-nav-toggle');
	$(window).resize(function(){
		/* call floating header on resize */
		floatHeader('#header-nav-container', '.page-header', '.nav-sections','#web-nav-toggle');
	});
	
	
	/*******mbl search*****/ 
	var headerContentObj = $('.header.content');  
	var mbl_SearchBtnObj = headerContentObj.find('.mbl_search_btn');
	if(mbl_SearchBtnObj.length){
		mbl_SearchBtnObj.click(function(){
			$(this).toggleClass('active');
			var searchBlockObj = headerContentObj.find('.block-search');
			searchBlockObj.toggleClass('active');
		});
	}
	/*******mbl search*****/
	
	/*******web nav with sticky*****/
	$('#web-nav-toggle').click(function(){
		$(this).stop(0).toggleClass('open');
		if($(this).hasClass('open')) {
			$('.nav-sections').show();
		} else {
			$('.nav-sections').hide();
		}
	});
	/*******web nav with sticky*****/
	
	
});
