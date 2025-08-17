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
        add_action('woocommerce_process_product_meta', array($this, 'save_ilok_checkbox'));
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
            'value' => $ilok_licensed ? 'yes' : 'no',
            'cbvalue' => 'yes'
        ));

        echo '</div>';
    }

    public function save_ilok_checkbox($post_id)
    {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $ilok_licensed = isset($_POST['_ilok_licensed']) && $_POST['_ilok_licensed'] === 'yes' ? 'yes' : 'no';
        update_post_meta($post_id, '_ilok_licensed', sanitize_text_field($ilok_licensed));
    }
}