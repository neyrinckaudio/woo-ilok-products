jQuery(document).ready(function($) {
    let validatedUserId = null;
    let isValidating = false;

    // Handle validation button click
    $('#validate_ilok_user_id').on('click', function(e) {
        e.preventDefault();
        validateIlokUserId();
    });

    // Handle Enter key in input field
    $('#ilok_user_id').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            validateIlokUserId();
        }
    });

    // Reset validation when input changes
    $('#ilok_user_id').on('input', function() {
        const currentValue = $(this).val();
        if (validatedUserId !== currentValue) {
            resetValidation();
        }
    });

    // Validate before add to cart
    $('form.cart').on('submit', function(e) {
        const userId = $('#ilok_user_id').val();
        
        if (!userId || userId.trim() === '') {
            e.preventDefault();
            showMessage(woo_ilok_ajax.messages.required, 'error');
            return false;
        }

        if (validatedUserId !== userId) {
            e.preventDefault();
            showMessage('Please validate your iLok User ID before adding to cart.', 'error');
            return false;
        }
    });

    function validateIlokUserId() {
        const userId = $('#ilok_user_id').val().trim();
        const messageDiv = $('#ilok_validation_message');
        const validateButton = $('#validate_ilok_user_id');

        if (!userId) {
            showMessage(woo_ilok_ajax.messages.required, 'error');
            return;
        }

        // Client-side validation
        if (userId.length > 32) {
            showMessage('iLok User ID must be 32 characters or less.', 'error');
            return;
        }

        if (userId.indexOf(' ') !== -1) {
            showMessage('iLok User ID cannot contain spaces.', 'error');
            return;
        }

        if (isValidating) {
            return;
        }

        isValidating = true;
        validateButton.prop('disabled', true);
        showMessage(woo_ilok_ajax.messages.validating, 'info');

        // AJAX validation
        $.ajax({
            url: woo_ilok_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'validate_ilok_user_id',
                user_id: userId,
                nonce: woo_ilok_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    validatedUserId = userId;
                    showMessage(response.data.message, 'success');
                    updateAddToCartButton(true);
                } else {
                    showMessage(response.data.message, 'error');
                    updateAddToCartButton(false);
                }
            },
            error: function() {
                showMessage(woo_ilok_ajax.messages.error, 'error');
                updateAddToCartButton(false);
            },
            complete: function() {
                isValidating = false;
                validateButton.prop('disabled', false);
            }
        });
    }

    function showMessage(message, type) {
        const messageDiv = $('#ilok_validation_message');
        const colors = {
            'success': '#46b450',
            'error': '#dc3232',
            'info': '#00a0d2'
        };

        messageDiv.html(message)
                 .css('color', colors[type] || '#666')
                 .show();
    }

    function resetValidation() {
        validatedUserId = null;
        $('#ilok_validation_message').hide();
        updateAddToCartButton(false);
    }

    function updateAddToCartButton(isValid) {
        const addToCartButton = $('.single_add_to_cart_button');
        
        if (isValid) {
            addToCartButton.removeClass('woo-ilok-disabled')
                          .prop('disabled', false);
        } else {
            addToCartButton.addClass('woo-ilok-disabled');
        }
    }

    // Initial state - disable add to cart until validation
    $(document).ready(function() {
        if ($('#ilok_user_id').length > 0) {
            updateAddToCartButton(false);
        }
    });
});