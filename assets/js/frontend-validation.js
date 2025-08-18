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
        const userIdInput = $('#ilok_user_id');
        const loadingOverlay = $('.woo-ilok-loading-overlay');

        if (!userId) {
            showMessage(woo_ilok_ajax.messages.required, 'error');
            userIdInput.removeClass('valid').addClass('error');
            return;
        }

        // Client-side validation
        if (userId.length > 32) {
            showMessage(woo_ilok_ajax.messages.format_error, 'error');
            userIdInput.removeClass('valid').addClass('error');
            return;
        }

        if (userId.indexOf(' ') !== -1) {
            showMessage(woo_ilok_ajax.messages.format_error, 'error');
            userIdInput.removeClass('valid').addClass('error');
            return;
        }

        if (isValidating) {
            return;
        }

        isValidating = true;
        validateButton.prop('disabled', true).addClass('validating');
        userIdInput.removeClass('valid error');
        showMessage(woo_ilok_ajax.messages.validating, 'info');
        loadingOverlay.addClass('active');

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
                    $('#ilok_user_id_validated').val('1');
                    showMessage(response.data.message, 'success');
                    userIdInput.addClass('valid');
                    updateAddToCartButton(true);
                } else {
                    showMessage(response.data.message, 'error');
                    $('#ilok_user_id_validated').val('0');
                    userIdInput.addClass('error');
                    updateAddToCartButton(false);
                }
            },
            error: function() {
                showMessage(woo_ilok_ajax.messages.error, 'error');
                $('#ilok_user_id_validated').val('0');
                userIdInput.addClass('error');
                updateAddToCartButton(false);
            },
            complete: function() {
                isValidating = false;
                validateButton.prop('disabled', false).removeClass('validating');
                loadingOverlay.removeClass('active');
            }
        });
    }

    function showMessage(message, type) {
        const messageDiv = $('#ilok_validation_message');
        
        messageDiv.removeClass('success error info')
                 .addClass(type)
                 .html(message)
                 .slideDown(300);
    }

    function resetValidation() {
        validatedUserId = null;
        $('#ilok_user_id_validated').val('0');
        $('#ilok_validation_message').slideUp(200);
        $('#ilok_user_id').removeClass('valid error');
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