jQuery(document).ready(function($) {
    'use strict';

    // Function to toggle iLok tab visibility
    function toggleIlokTab() {
        var isChecked = $('#ilok_product').is(':checked');
        // Try multiple selectors to find the tab
        var ilokTab = $('.woocommerce_options_panel ul li.ilok_licensing_tab, li.ilok_licensing_tab, .ilok_licensing_tab');
        var ilokPanel = $('#ilok_licensing_options');

        if (isChecked) {
            ilokTab.show();
        } else {
            ilokTab.hide();
            // If the iLok tab is currently active, switch to General tab
            if (ilokTab.hasClass('active')) {
                $('.woocommerce_options_panel ul li.general_options a, li.general_options a, .general_tab a').trigger('click');
            }
        }
    }

    // Wait for WooCommerce to initialize, then run initial check
    setTimeout(function() {
        toggleIlokTab();
    }, 100);

    // Listen for checkbox changes
    $('#ilok_product').on('change', function() {
        toggleIlokTab();
    });

    // Handle WooCommerce product type changes that might affect tab visibility
    $('select#product-type').on('change', function() {
        setTimeout(function() {
            toggleIlokTab();
        }, 200);
    });
});