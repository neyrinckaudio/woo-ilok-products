<?php
/**
 * Plugin Name: WooCommerce iLok Products
 * Plugin URI: https://github.com/example/woo-ilok-products
 * Description: Extend WooCommerce product configuration to support iLok licensing metadata for digital audio software products.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * WC requires at least: 4.0
 * WC tested up to: 8.9
 * Text Domain: woo-ilok-products
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

if (!defined('WOO_ILOK_PRODUCTS_FILE')) {
    define('WOO_ILOK_PRODUCTS_FILE', __FILE__);
}

if (!defined('WOO_ILOK_PRODUCTS_PATH')) {
    define('WOO_ILOK_PRODUCTS_PATH', plugin_dir_path(__FILE__));
}

if (!defined('WOO_ILOK_PRODUCTS_URL')) {
    define('WOO_ILOK_PRODUCTS_URL', plugin_dir_url(__FILE__));
}

if (!defined('WOO_ILOK_PRODUCTS_VERSION')) {
    define('WOO_ILOK_PRODUCTS_VERSION', '1.0.0');
}

if (!class_exists('WooIlokProducts')) {

    class WooIlokProducts
    {
        private static $instance = null;

        public static function get_instance()
        {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function __construct()
        {
            add_action('plugins_loaded', array($this, 'init'));
        }

        public function init()
        {
            if (!$this->check_requirements()) {
                return;
            }

            $this->load_textdomain();
            $this->includes();
            $this->init_hooks();
        }

        private function check_requirements()
        {
            if (!class_exists('WooCommerce')) {
                add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
                return false;
            }

            if (version_compare(WC_VERSION, '4.0', '<')) {
                add_action('admin_notices', array($this, 'woocommerce_version_notice'));
                return false;
            }

            return true;
        }

        public function woocommerce_missing_notice()
        {
            echo '<div class="error"><p><strong>' . esc_html__('WooCommerce iLok Products', 'woo-ilok-products') . '</strong> ' . esc_html__('requires WooCommerce to be installed and active.', 'woo-ilok-products') . '</p></div>';
        }

        public function woocommerce_version_notice()
        {
            echo '<div class="error"><p><strong>' . esc_html__('WooCommerce iLok Products', 'woo-ilok-products') . '</strong> ' . esc_html__('requires WooCommerce version 4.0 or higher.', 'woo-ilok-products') . '</p></div>';
        }

        private function load_textdomain()
        {
            load_plugin_textdomain('woo-ilok-products', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        private function includes()
        {
            require_once WOO_ILOK_PRODUCTS_PATH . 'includes/class-woo-ilok-product-admin.php';
            require_once WOO_ILOK_PRODUCTS_PATH . 'includes/class-woo-ilok-order-handler.php';
        }

        private function init_hooks()
        {
            new WooIlokProductAdmin();
            new WooIlokOrderHandler();
        }
    }

    WooIlokProducts::get_instance();
}