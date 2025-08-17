<?php

defined('ABSPATH') || exit;

class WooIlokOrderHandler
{
    public function __construct()
    {
        add_action('init', array($this, 'init'));
    }

    public function init()
    {
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'copy_ilok_metadata_to_order_item'), 10, 4);
        add_action('woocommerce_new_order_item', array($this, 'copy_ilok_metadata_on_manual_order'), 10, 3);
    }

    public function copy_ilok_metadata_to_order_item($item, $cart_item_key, $values, $order)
    {
        if (!$item instanceof WC_Order_Item_Product) {
            return;
        }

        $product_id = $item->get_product_id();
        $variation_id = $item->get_variation_id();
        
        // Use variation ID if available, otherwise use product ID
        $meta_product_id = $variation_id ? $variation_id : $product_id;
        
        $this->add_ilok_metadata_to_order_item($item, $meta_product_id);
    }

    public function copy_ilok_metadata_on_manual_order($item_id, $item, $order_id)
    {
        if (!$item instanceof WC_Order_Item_Product) {
            return;
        }

        $product_id = $item->get_product_id();
        $variation_id = $item->get_variation_id();
        
        // Use variation ID if available, otherwise use product ID
        $meta_product_id = $variation_id ? $variation_id : $product_id;
        
        $this->add_ilok_metadata_to_order_item($item, $meta_product_id);
    }

    private function add_ilok_metadata_to_order_item($item, $product_id)
    {
        // Get iLok metadata from product
        $ilok_licensed = get_post_meta($product_id, '_ilok_licensed', true);
        $ilok_sku_guid = get_post_meta($product_id, '_ilok_sku_guid', true);

        // Only add metadata if product is iLok licensed
        if ($ilok_licensed === 'yes') {
            // Copy metadata to order item with consistent keys
            $item->add_meta_data('_ilok_licensed', $ilok_licensed, true);
            
            if (!empty($ilok_sku_guid)) {
                $item->add_meta_data('_ilok_sku_guid', $ilok_sku_guid, true);
            }
            
            // Save the item to persist metadata
            $item->save();
        }
    }
}