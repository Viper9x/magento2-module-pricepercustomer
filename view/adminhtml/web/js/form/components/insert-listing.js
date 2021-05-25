define([
    'Magento_Ui/js/form/components/insert-listing',
    'underscore'
], function (Insert, _) {
    'use strict';

    return Insert.extend({
        /**
         * Select customer for Rule
         *
         * @param {Object} data - customer
         */
        selectCustomer: function (data) {
            // Update customer fields value
            jQuery('input[name="customer_id"]').val(data['entity_id']).change();
            jQuery('input[name="customer_name"]').val(data['customer_name']).change();
            jQuery('input[name="customer_email"]').val(data['customer_email']).change();
        },

        /**
         * Select product for Rule
         *
         * @param {Object} data - product
         */
        selectProduct: function (data) {
            // Update product fields value
            jQuery('input[name="product_id"]').val(data['entity_id']).change();
            jQuery('input[name="product_name"]').val(data['product_name']).change();
            jQuery('input[name="product_sku"]').val(data['product_sku']).change();
            jQuery('input[name="original_price"]').val(data['original_price']).change();
        },
    });
});
