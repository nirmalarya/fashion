/**
 * Copyright © 2013-2017 ScandicDesi. All rights reserved.
 */
define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            this.newsletter = customerData.get('newsletter');
        }
    });
});