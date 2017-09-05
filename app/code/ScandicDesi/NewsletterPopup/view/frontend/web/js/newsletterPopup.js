/**
 * Copyright © 2013-2017 ScandicDesi. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "underscore",
    "jquery/ui",
    "Magento_Ui/js/modal/modal",
    "mage/cookies",
    "mage/validation"
], function($, _) {
    "use strict";

    var newsletterPopup = {
        options: {
            actionSelector: ".action.subscribe",
            formSelector: ""
        },
        init: function (options, element) {
            var _this = this;
            _this.options = $.extend(_this.options, options);
            _this.options.element = element;
            if (_this.options.formSelector) {
                _this.form = $(_this.options.formSelector);
            } else {
                _this.form = $(_this.options.element).closest('form');
            }
            if (_this.isPopupVisible()) {
                _this.popup = jQuery(_this.options.element).modal({
                    title: _('NewsLetter Popup'),
                    buttons: []
                });
                _this.popup.modal('openModal');

                $(document).on('submit', _this.form, function() {
                    _this.stopPopup();
                })
            }
        },
        isPopupVisible: function () {
            return !$.mage.cookies.get('newsletterPopup');
        },
        stopPopup: function () {
            var _this = this;
            if (_this.form.validation('isValid')) {
                $.mage.cookies.set('newsletterPopup', true);
            }
        }
    };

    return function(config, element) {
        newsletterPopup.init(config, element);
    }
});