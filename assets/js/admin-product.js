jQuery(document).ready(function($) {
    'use strict';

    // Function to toggle iLok tab visibility
    function toggleIlokTab() {
        var isChecked = $('#_ilok_licensed').is(':checked');
        var ilokTab = $('.ilok_licensing_tab');
        var ilokPanel = $('#ilok_licensing_options');

        if (isChecked) {
            ilokTab.show();
        } else {
            ilokTab.hide();
            // If the iLok tab is currently active, switch to General tab
            if (ilokTab.hasClass('active')) {
                $('.general_tab a').trigger('click');
            }
        }
    }

    // Initial check on page load
    toggleIlokTab();

    // Listen for checkbox changes
    $('#_ilok_licensed').on('change', function() {
        toggleIlokTab();
    });

    // Handle WooCommerce product type changes that might affect tab visibility
    $('select#product-type').on('change', function() {
        setTimeout(function() {
            toggleIlokTab();
        }, 100);
    });
});