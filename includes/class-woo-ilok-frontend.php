<?php

defined('ABSPATH') || exit;

class WooIlokFrontend
{
    public function __construct()
    {
        $this->init_hooks();
    }

    private function init_hooks()
    {
        // Add iLok User ID field to product pages - use a hook that's inside the form
        add_action('woocommerce_before_add_to_cart_button', array($this, 'display_ilok_user_id_field'));
        
        // Prevent add to cart without valid iLok User ID
        add_filter('woocommerce_add_to_cart_validation', array($this, 'validate_ilok_user_id_before_cart'), 10, 3);
        
        // Store iLok User ID in cart item data
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_ilok_user_id_to_cart_item'), 10, 3);
        
        // Store iLok User ID in order item metadata
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'add_ilok_user_id_to_order_item'), 10, 4);
        
        // Enqueue frontend scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        
        // Handle AJAX validation request
        add_action('wp_ajax_validate_ilok_user_id', array($this, 'ajax_validate_ilok_user_id'));
        add_action('wp_ajax_nopriv_validate_ilok_user_id', array($this, 'ajax_validate_ilok_user_id'));
    }

    /**
     * Display iLok User ID field on product pages for iLok-licensed products
     */
    public function display_ilok_user_id_field()
    {
        global $product;

        if (!$product || !$this->is_ilok_licensed_product($product)) {
            return;
        }

        ?>
        <div class="woo-ilok-user-id-section" style="margin: 20px 0;">
            <div class="woo-ilok-user-id-field">
                <label for="ilok_user_id"><?php esc_html_e('iLok User ID:', 'woo-ilok-products'); ?> <span style="color: red;">*</span></label>
                <div style="display: flex; gap: 10px; margin-top: 5px;">
                    <input type="text" 
                           autocomplete="off" 
                           autocapitalize="off"
                           id="ilok_user_id" 
                           name="ilok_user_id" 
                           placeholder="<?php esc_attr_e('Enter your iLok User ID', 'woo-ilok-products'); ?>"
                           maxlength="32" 
                           style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" 
                           required />
                    <button type="button" 
                            id="validate_ilok_user_id" 
                            class="button"
                            style="padding: 8px 16px;">
                        <?php esc_html_e('Validate', 'woo-ilok-products'); ?>
                    </button>
                </div>
                <input type="hidden" id="ilok_user_id_validated" name="ilok_user_id_validated" value="0" />
                <div id="ilok_validation_message" style="margin-top: 10px; font-size: 14px;"></div>
                <p class="description" style="margin-top: 5px; font-size: 12px; color: #666;">
                    <?php echo wp_kses(__('Your iLok User ID is required. If you do not have an iLok User ID, create an account at <a href="https://www.ilok.com/#!registration" target="_blank">iLok.com</a>.', 'woo-ilok-products'), array('a' => array('href' => array(), 'target' => array()))); ?>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Check if product is iLok licensed
     */
    private function is_ilok_licensed_product($product)
    {
        if (!$product) {
            return false;
        }

        $product_id = $product->get_id();
        $is_ilok_licensed = get_post_meta($product_id, '_ilok_licensed', true);
        
        return 'yes' === $is_ilok_licensed;
    }

    /**
     * Validate iLok User ID before adding to cart
     */
    public function validate_ilok_user_id_before_cart($valid, $product_id, $quantity)
    {
        $product = wc_get_product($product_id);
        
        if (!$this->is_ilok_licensed_product($product)) {
            return $valid;
        }

        $ilok_user_id = isset($_POST['ilok_user_id']) ? sanitize_text_field($_POST['ilok_user_id']) : '';
        $is_validated = isset($_POST['ilok_user_id_validated']) ? sanitize_text_field($_POST['ilok_user_id_validated']) : '0';
        
        if (empty($ilok_user_id)) {
            wc_add_notice(__('iLok User ID is required for this product.', 'woo-ilok-products'), 'error');
            return false;
        }

        // Validate format (no spaces, max 32 characters)
        if (!$this->validate_ilok_user_id_format($ilok_user_id)) {
            wc_add_notice(__('Invalid iLok User ID format. Must be 32 characters or less with no spaces.', 'woo-ilok-products'), 'error');
            return false;
        }

        // Check if User ID was validated via AJAX or hidden field
        $validated_user_id = WC()->session->get('validated_ilok_user_id');
        if ($validated_user_id !== $ilok_user_id && $is_validated !== '1') {
            wc_add_notice(__('Please validate your iLok User ID before adding to cart.', 'woo-ilok-products'), 'error');
            return false;
        }

        return $valid;
    }

    /**
     * Add iLok User ID to cart item data
     */
    public function add_ilok_user_id_to_cart_item($cart_item_data, $product_id, $variation_id)
    {
        $product = wc_get_product($product_id);
        
        if (!$this->is_ilok_licensed_product($product)) {
            return $cart_item_data;
        }

        $ilok_user_id = isset($_POST['ilok_user_id']) ? sanitize_text_field($_POST['ilok_user_id']) : '';
        
        if (!empty($ilok_user_id)) {
            $cart_item_data['ilok_user_id'] = $ilok_user_id;
        }

        return $cart_item_data;
    }

    /**
     * Add iLok User ID to order item metadata
     */
    public function add_ilok_user_id_to_order_item($item, $cart_item_key, $values, $order)
    {
        if (isset($values['ilok_user_id'])) {
            $item->add_meta_data('iLok User ID', $values['ilok_user_id'], true);
        }
    }

    /**
     * Validate iLok User ID format
     */
    private function validate_ilok_user_id_format($user_id)
    {
        // Check if empty
        if (empty(trim($user_id))) {
            return false;
        }

        // Check length
        if (strlen($user_id) > 32) {
            return false;
        }

        // Check for spaces
        if (strpos($user_id, ' ') !== false) {
            return false;
        }

        return true;
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts()
    {
        if (!is_product()) {
            return;
        }

        global $product;
        if (!$this->is_ilok_licensed_product($product)) {
            return;
        }

        wp_enqueue_script(
            'woo-ilok-frontend',
            WOO_ILOK_PRODUCTS_URL . 'assets/js/frontend-validation.js',
            array('jquery'),
            WOO_ILOK_PRODUCTS_VERSION,
            true
        );

        wp_localize_script('woo-ilok-frontend', 'woo_ilok_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('validate_ilok_user_id'),
            'messages' => array(
                'validating' => __('Validating...', 'woo-ilok-products'),
                'valid' => __('iLok User ID is valid!', 'woo-ilok-products'),
                'invalid' => __('Invalid iLok User ID.', 'woo-ilok-products'),
                'error' => __('Validation error. Please try again.', 'woo-ilok-products'),
                'required' => __('Please enter your iLok User ID.', 'woo-ilok-products')
            )
        ));
    }

    /**
     * Handle AJAX validation request
     */
    public function ajax_validate_ilok_user_id()
    {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'validate_ilok_user_id')) {
            wp_die(__('Security check failed', 'woo-ilok-products'));
        }

        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : '';

        if (empty($user_id)) {
            wp_send_json_error(array(
                'message' => __('iLok User ID is required.', 'woo-ilok-products')
            ));
        }

        // Validate format
        if (!$this->validate_ilok_user_id_format($user_id)) {
            wp_send_json_error(array(
                'message' => __('Invalid iLok User ID format. Must be 32 characters or less with no spaces.', 'woo-ilok-products')
            ));
        }

        // Validate with wp-edenremote plugin if available
        $validation_result = $this->validate_with_edenremote($user_id);

        if ($validation_result['success']) {
            // Store validated User ID in session
            WC()->session->set('validated_ilok_user_id', $user_id);
            
            wp_send_json_success(array(
                'message' => __('iLok User ID is valid!', 'woo-ilok-products')
            ));
        } else {
            wp_send_json_error(array(
                'message' => $validation_result['message']
            ));
        }
    }

    /**
     * Validate iLok User ID with wp-edenremote plugin
     */
    private function validate_with_edenremote($user_id)
    {
        // Check if wp-edenremote plugin is available
        if (!function_exists('wp_edenremote_validate_user_id')) {
            // Fallback: basic validation without external service
            return array(
                'success' => true,
                'message' => __('iLok User ID format is valid (external validation not available).', 'woo-ilok-products')
            );
        }

        try {
            // Call wp-edenremote validation function
            $result = wp_edenremote_validate_user_id($user_id);
            
            if ($result && isset($result['valid']) && $result['valid']) {
                return array(
                    'success' => true,
                    'message' => __('iLok User ID is valid!', 'woo-ilok-products')
                );
            } else {
                $error_message = isset($result['message']) ? $result['message'] : __('Invalid iLok User ID.', 'woo-ilok-products');
                return array(
                    'success' => false,
                    'message' => $error_message
                );
            }
        } catch (Exception $e) {
            return array(
                'success' => false,
                'message' => __('Validation service error. Please try again.', 'woo-ilok-products')
            );
        }
    }
}