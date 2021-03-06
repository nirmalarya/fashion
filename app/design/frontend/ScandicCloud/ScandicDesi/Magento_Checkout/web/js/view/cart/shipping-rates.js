/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [   'jquery',
        'ko',
        'underscore',
        'uiComponent',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/checkout-data'
    ],
    function (
        $,
        ko,
        _,
        Component,
        shippingService,
        priceUtils,
        quote,
        selectShippingMethodAction,
        checkoutData
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/cart/shipping-rates'
            },
            isVisible: ko.observable(!quote.isVirtual()),
            isLoading: shippingService.isLoading,
            shippingRates: shippingService.getShippingRates(),
            shippingRateGroups: ko.observableArray([]),
            selectedShippingMethod: ko.computed(function () {
                    var shippingMethodExist = quote.shippingMethod() ?
                            quote.shippingMethod()['method_code'] :
                            null;
                    if(shippingMethodExist != null) {  
                        var sMethodElement = $("#s_method_"+shippingMethodExist)
                        if(sMethodElement.length){
                            var sMethodElementClosestCartSummary = sMethodElement.closest('.cart-summary');
                            var sMethodElementClosestField = sMethodElement.closest('.field');
                            
                            sMethodElementClosestCartSummary.find('.field').removeClass('active-method');
                            sMethodElementClosestField.addClass('active-method');
                            
                        }
                    }
                
                    return quote.shippingMethod() ?
                        quote.shippingMethod()['carrier_code'] + '_' + quote.shippingMethod()['method_code'] :
                        null;
                }
            ),
            selectedShippingMethodHeaderEvent: function(carrier_code,method_code){
                var quote_c_m =  quote.shippingMethod() ?
                        quote.shippingMethod()['carrier_code'] + '_' + quote.shippingMethod()['method_code'] :
                        null;
                console.log(carrier_code+'_'+method_code+'  === '+quote_c_m);
                return (carrier_code+'_'+method_code === quote_c_m)?1:0;
            },

            /**
             * @override
             */
            initObservable: function () {
                var self = this;
                this._super();

                this.shippingRates.subscribe(function (rates) {
                    self.shippingRateGroups([]);
                    _.each(rates, function (rate) {
                        var carrierTitle = rate['carrier_title'];

                        if (self.shippingRateGroups.indexOf(carrierTitle) === -1) {
                            self.shippingRateGroups.push(carrierTitle);
                        }
                    });
                });

                return this;
            },

            /**
             * Get shipping rates for specific group based on title.
             * @returns Array
             */
            getRatesForGroup: function (shippingRateGroupTitle) {
                return _.filter(this.shippingRates(), function (rate) {
                    return shippingRateGroupTitle === rate['carrier_title'];
                });
            },

            /**
             * Format shipping price.
             * @returns {String}
             */
            getFormattedPrice: function (price) {
                return priceUtils.formatPrice(price, quote.getPriceFormat());
            },

            /**
             * Set shipping method.
             * @param {String} methodData
             * @returns bool
             */
            selectShippingMethod: function (methodData) {
                selectShippingMethodAction(methodData);
                checkoutData.setSelectedShippingRate(methodData['carrier_code'] + '_' + methodData['method_code']);
                                  
                var sMethodElement = $("#s_method_"+methodData['method_code'])
                if(sMethodElement.length){
                    var sMethodElementClosestCartSummary = sMethodElement.closest('.cart-summary');
                    var sMethodElementClosestField = sMethodElement.closest('.field');
                    
                    sMethodElementClosestCartSummary.find('.field').removeClass('active-method');
                    sMethodElementClosestField.addClass('active-method');
                    
                }
                   
                
                    
                return true;
            }
        });
    }
);
