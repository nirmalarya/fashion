/**
 * Copyright © 2013-2017 ScandicDesi. All rights reserved.
 */
define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'mage/translate',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/cart/totals-processor/default'
], function ($,Component, customerData, $t, quote, priceUtils, totalsDefaultProvider) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            this.cartOffers = customerData.get('cart-offers');
            this.cart = customerData.get('cart');
            totalsDefaultProvider.estimateTotals(quote.shippingAddress());
            this.cartData = customerData.get('cart-data');
        },
        getGrandTotal: function () {
            var cartTotals = this.cartData().totals;
            var grandTotal = 0;
            if (cartTotals !== null) {
                var baseGrandTotal = cartTotals.base_grand_total;
                grandTotal = this.formatPrice(baseGrandTotal);
            }
            return grandTotal;
        },
        getSubtotalBalance: function () {
            var cartTotals = this.cartData().totals;
            var freeShippingThreshold = this.cartOffers().shipping.free_shipping_threshold;
            var subtotal = cartTotals !== null ? cartTotals.base_subtotal_incl_tax : freeShippingThreshold;
            if (cartTotals === null || this.cart().summary_count === cartTotals.items_qty) {
                return Math.max(freeShippingThreshold - subtotal, 0);
            }
            return 0;
        },
        getCartMessage: function () {
            var message = $t(this.cartOffers().shipping.message);
            var balance = this.getSubtotalBalance();
            return message.replace('%1', this.formatPrice(balance));
        },
        formatPrice: function (price) {
            return priceUtils.formatPrice(price, quote.getPriceFormat());
        }
    });
});