/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint jquery:true*/
define([
    "jquery",
    "underscore",
    "jquery/ui",
    "Magento_Ui/js/modal/modal"
], function($, __) {
    "use strict";

    $.widget('mage.newsletterPopup', {
        options: {
            localStorageKey: "recently-viewed-products",
            productBlock: "#widget_viewed_item",
            viewedContainer: "ol"
        },
        initialize: function (options) {
            var _this = this;
            _this.options = $.extend(_this.options, options);
            console.log(_this.options, _this.element());
            _this.popup = jQuery(_this.element()).modal({
                title: __('NewsLetter Popup'),
                type: 'slide',
                buttons: [{
                    text: __('OK'),
                    'class': 'action-primary',
                    click: function () {
                        _this.onConfirmBtn();
                    }
                }]
            });
        },
        test: function () {
            console.log('test test');
        }
    });
    return $.mage.newsletterPopup.test();
});