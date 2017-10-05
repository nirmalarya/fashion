/**
 * Copyright © 2013-2017 ScandicDesi. All rights reserved.
 */
define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function ($,Component, customerData) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            this.cartOffers = customerData.get('cart-offers');
            
            if(this.cartOffers) {
                $('.block-cart-offer .message-area').addClass('success');
            }
        }
    });
});