<?php

defined('ABSPATH') || exit;

class WooIlokProductAdmin
{
    public function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    public function init()
    {
        if (is_admin()) {
            $this->init_hooks();
        }
    }

    private function init_hooks()
    {
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_ilok_checkbox'));
        add_filter('woocommerce_product_data_tabs', array($this, 'add_ilok_tab'));
        add_action('woocommerce_product_data_panels', array($this, 'add_ilok_tab_content'));
        add_action('woocommerce_process_product_meta', array($this, 'save_ilok_data'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_ilok_checkbox()
    {
        global $post;

        $ilok_licensed = get_post_meta($post->ID, '_ilok_licensed', true);

        echo '<div class="options_group">';
        
        woocommerce_wp_checkbox(array(
            'id' => '_ilok_licensed',
            'label' => __('iLok Licensed', 'woo-ilok-products'),
            'description' => __('Check this box if this product requires iLok licensing.', 'woo-ilok-products'),
            'value' => $ilok_licensed,
            'cbvalue' => 'yes'
        ));

        echo '</div>';
    }

    public function save_ilok_data($post_id)
    {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save iLok Licensed checkbox
        $ilok_licensed = isset($_POST['_ilok_licensed']) && $_POST['_ilok_licensed'] === 'yes' ? 'yes' : 'no';
        update_post_meta($post_id, '_ilok_licensed', sanitize_text_field($ilok_licensed));

        // Save SKU Guid field
        $ilok_sku_guid = isset($_POST['_ilok_sku_guid']) ? sanitize_text_field($_POST['_ilok_sku_guid']) : '';
        update_post_meta($post_id, '_ilok_sku_guid', $ilok_sku_guid);
    }

    public function add_ilok_tab($tabs)
    {
        $tabs['ilok_licensing'] = array(
            'label' => __('iLok Licensing', 'woo-ilok-products'),
            'target' => 'ilok_licensing_options',
            'class' => array('ilok_licensing_tab'),
            'priority' => 60
        );

        return $tabs;
    }

    public function add_ilok_tab_content()
    {
        global $post;

        echo '<div id="ilok_licensing_options" class="panel woocommerce_options_panel hidden">';
        echo '<div class="options_group">';
        
        echo '<p>' . esc_html__('Configure iLok licensing settings for this product.', 'woo-ilok-products') . '</p>';
        
        woocommerce_wp_text_input(array(
            'id' => '_ilok_sku_guid',
            'label' => __('SKU Guid', 'woo-ilok-products'),
            'description' => __('Enter the iLok SKU Guid for this product. This field is required when iLok Licensed is enabled.', 'woo-ilok-products'),
            'desc_tip' => true,
            'type' => 'text',
            'custom_attributes' => array(
                'maxlength' => '255'
            ),
            'value' => get_post_meta($post->ID, '_ilok_sku_guid', true)
        ));
        
        echo '</div>';
        echo '</div>';
    }

    public function enqueue_admin_scripts($hook)
    {
        global $post_type;

        if (($hook === 'post.php' || $hook === 'post-new.php') && $post_type === 'product') {
            wp_enqueue_script(
                'woo-ilok-admin-product',
                WOO_ILOK_PRODUCTS_URL . 'assets/js/admin-product.js',
                array('jquery'),
                WOO_ILOK_PRODUCTS_VERSION,
                true
            );
        }
    }
}