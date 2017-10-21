/**
 * Copyright © 2013-2017 ScandicDesi. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "underscore",
    "Magento_Customer/js/customer-data",
    "jquery/ui",
    "Magento_Ui/js/modal/modal",
    "mage/cookies",
    "mage/validation"
], function($, _, customerData) {
    "use strict";

    $.widget('scandicdesi.newsletterPopup', {
        defaults: {
            actionSelector: ".action.subscribe",
            formSelector: "",
            popupDelay: 0
        },
        _create: function () {
            var _this = this;
            _this.options = $.extend(_this.defaults, _this.options);
            _this.options.popupDelay *= 1000;
            if (_this.options.formSelector) {
                _this.form = $(_this.options.formSelector);
            } else {
                _this.form = $(_this.element).closest('form');
            }

            _this.popup = jQuery(_this.element).modal({
                title: _('NewsLetter Popup'),
                modalClass: "newsletter-model-popup",
                buttons: []
            });

            _this.init();
        },
        init: function () {
            var _this = this;
            if (_this.isPopupVisible()) {
                setTimeout(function() {
                    _this.popup.modal('openModal');
                }, _this.options.popupDelay);

                $(document).on('submit', _this.form, function() {
                    _this.stopPopup();
                });

                _this.popup.on('modalclosed', function() {
                    _this.stopPopup();
                });
            }
        },
        isPopupVisible: function () {
            return !customerData.get("newsletter")().subscribed;
        },
        stopPopup: function () {
            var _this = this;
            if (_this.form.validation('isValid')) {
                var newsletter = customerData.get("newsletter")();
                newsletter.subscribed = true;
                customerData.set("newsletter", newsletter);
            }
        }
    });

    return $.scandicdesi.newsletterPopup;
});