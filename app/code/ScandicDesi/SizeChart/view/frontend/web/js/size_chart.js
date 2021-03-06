/**
 * Copyright © 2013-2017 ScandicDesi. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    "jquery",
    "underscore",
    "jquery/ui",
    "Magento_Ui/js/modal/modal"
], function($, _) {
    "use strict";

    var sizeChart = {
        options: {
            actionSelector: "[data-role=sizechart]",
            bodyClass: "size-chart-catalog-product-view"
        },
        init: function (options, element) {
            var _this = this;
            if(_this.options.bodyClass) {
                $('body').addClass(_this.options.bodyClass);
            }
            _this.options = $.extend(_this.options, options);
            _this.options.element = element;
            _this.popup = jQuery(_this.options.element).modal({
                title: _('Size Chart'),
                modalClass: "sizechart-modal-popup",
                buttons: [],
                responsive: true
            });
            $(document).on('click', _this.options.actionSelector, function (evt) {
                evt.preventDefault();
                _this.popup.modal('openModal');
            })
        }
    };

    return function(config, element) {
        sizeChart.init(config, element);
    }
});