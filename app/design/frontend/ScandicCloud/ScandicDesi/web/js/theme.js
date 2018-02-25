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
    $('.header.content .account_li.wrapper > .header.links').clone().appendTo('#store\\.links');

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

        var logoContainer = containerFixed.find('.logo');
        if(!containerFixed.hasClass('onload-fixed')){
			containerFixed.addClass('onload-fixed')
		}
        var containerFixedH = containerFixed.outerHeight();
        
        if(nav.attr('style') != undefined && ( !containerFixed.hasClass('fixed') || !($(window).width() >= 768) ) ) { // reset style for nav
			nav.removeAttr('style');				
		}
        
		if(!nav.is(':visible')){
			containerFixedH += nav.height();
		}
		
		
		
        var logoContainerH = logoContainer.height();
        var logoContainerImageH = logoContainer.find('> img, > svg').height();
        var logoDiffeneceH =  logoContainerImageH - logoContainerH;
        if(logoDiffeneceH > 5 && $(window).width() >= 768 ) { // logo small issue
            containerFixedH += logoDiffeneceH ;
        }
        
        container.height(containerFixedH);
		container.css('min-height',containerFixedH);
        
        
		
		
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
            if($(window).width() >= 768) {
                if(start) {
                    toggleMenu(start);
                } else {
                    toggleMenu(false);
                }
            }
        }
		
		var toggleMenu = function(hide) {
			var topmenu_ActiveLis = topmenu_Obj.find(' > li.active').length;
			if(hide && !navDepandEle_Obj.hasClass('open') && !topmenu_ActiveLis) {
				// nav.stop(0).slideUp();
				nav.slideUp(); // required smooth transition for menu so remove stop
				
				navDepandEle_Obj.removeClass('open');
			} else {
				if(topmenu_ActiveLis){
					navDepandEle_Obj.addClass('open');
				}
				// nav.stop(0).slideDown();
				nav.slideDown();
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
            headerContentObj.find('.logo').toggleClass('active');
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





function qtyBox_UiEnabled($){
	var parentBlock;
	var newDiv;			
	var maxQtyLimit = 999;
	
	$('input.input-text.qty').each(function(i,el){
		parentBlock = $(el).parent();
		
		// if "inc-qtyBox" is exist means already qtyBox_UiEnabled is enabled
		if(!parentBlock.hasClass("inc-qtyBox")){ 
		
			newDiv = document.createElement('div');		
			newDiv.id = 'qtyBox'+i;
			newDiv.className  = "inc-qtyBox";
			
			var mainDivContent = parentBlock.html();
			var disableFirst = 'disabled';
			if($(el).val() > 1){
				disableFirst='';
			}
			
			var newDivContent='';
			newDivContent += ('<button type="button" class="inc-qtyBox-btn qty-decrease '+disableFirst+'" '+disableFirst+'>-</button>');
			newDivContent += mainDivContent;
			newDivContent += ('<button type="button" class="inc-qtyBox-btn  qty-increase">+</button>');
			
			
			newDiv.innerHTML = newDivContent; //set new input inside new div
			parentBlock.html('');  //erase default input
			parentBlock.append(newDiv); //show new div 
		}
	});
 
	$('.inc-qtyBox button.qty-increase').each(function(i,el){
		$(el).unbind('click.qtyIncreaseUiEnabled'+i);
		$(el).bind('click.qtyIncreaseUiEnabled'+i,function(event){
			var currentElement = null;
			currentElement = $(el).parent().find('input');
			currentElement_Value = parseInt(currentElement.val());
			//console.log(currentElement )
			
			var upObj =$(el);
			var dnObj =$(el).parent().find('button.qty-decrease');
			
			if(currentElement_Value == NaN){
				currentElement.val(1);
				dnObj.addClass('disabled').attr('disabled');
			}
			
				
			if(currentElement_Value < maxQtyLimit){
				currentElement.val(currentElement_Value + 1);
				dnObj.removeClass('disabled').removeAttr('disabled');
			}
			else{
				currentElement.val(maxQtyLimit);
				dnObj.removeClass('disabled').removeAttr('disabled');
			}
		});
	});
 
	$('.inc-qtyBox  button.qty-decrease').each(function(i,el){
		$(el).unbind('click.qtyDecreaseUiEnabled'+i);
		$(el).bind('click.qtyDecreaseUiEnabled'+i,function(event){
			var currentElement = null;
			currentElement = $(el).parent().find('input');
			currentElement_Value = parseInt(currentElement.val());
			//console.log(currentElement )
			
			var upObj =$(el).parent().find('button.qty-increase');
			var dnObj =$(el);
			
			if(currentElement_Value > maxQtyLimit){
				currentElement.val(maxQtyLimit);
				dnObj.removeClass('disabled').removeAttr('disabled');
				
			}else if(currentElement_Value > 1){
				currentElement.val(currentElement_Value - 1);
				dnObj.removeClass('disabled').removeAttr('disabled');
				
			}else{
				currentElement.val(1);
				dnObj.addClass('disabled').attr('disabled');
			}
		});
	});
}

