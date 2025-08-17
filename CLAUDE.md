# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress plugin called "woo-ilok-products" that extends WooCommerce to support iLok licensing metadata for digital audio software products. The plugin adds product configuration options for iLok licensing and stores metadata that can be accessed by other plugins for license fulfillment.

## Implementation Status

### Completed Core Features
- ✅ Plugin structure and activation/deactivation hooks
- ✅ "iLok Licensed" checkbox in Product Data metabox header
- ✅ "iLok Licensing" tab with progressive disclosure
- ✅ SKU Guid field with 255 character limit
- ✅ Server-side validation (required field, length limits)
- ✅ Product metadata saving (`_ilok_licensed`, `_ilok_sku_guid`)
- ✅ Order processing - metadata copy to order items
- ✅ JavaScript for dynamic tab show/hide

### Pending Tasks
- Security enhancements (nonce verification, input sanitization)
- UI styling to match WooCommerce admin
- Comprehensive testing across product types
- Plugin uninstall cleanup

## Core Architecture

### Plugin Structure
```
woo-ilok-products/
├── woo-ilok-products.php          # Main plugin file with singleton pattern
├── includes/
│   ├── class-woo-ilok-product-admin.php    # Product admin interface
│   └── class-woo-ilok-order-handler.php    # Order processing
├── assets/
│   ├── js/admin-product.js        # Progressive disclosure JavaScript
│   └── css/                       # Stylesheets (future)
└── languages/                     # Translation files
```

### Key Components
- **WooIlokProducts**: Main plugin class with dependency checking
- **WooIlokProductAdmin**: Handles product admin interface and validation
- **WooIlokOrderHandler**: Manages order processing and metadata copying
- **Progressive Disclosure**: JavaScript controls tab visibility based on checkbox

### Implemented Hooks
- `woocommerce_product_options_general_product_data` - Checkbox placement
- `woocommerce_product_data_tabs` - Tab registration
- `woocommerce_product_data_panels` - Tab content
- `woocommerce_process_product_meta` - Data saving and validation
- `woocommerce_checkout_create_order_line_item` - Order metadata copying
- `woocommerce_new_order_item` - Manual order support

### Metadata Schema
```php
// Product Meta
'_ilok_licensed' => 'yes' | 'no'    // String values for WooCommerce compatibility
'_ilok_sku_guid' => 'string'        // SKU Guid value (max 255 chars)

// Order Item Meta (copied from product)
'_ilok_licensed' => 'yes' | 'no'
'_ilok_sku_guid' => 'string'
```

## Development Requirements

### Compatibility
- WordPress: 5.0 minimum, 6.x+ recommended
- WooCommerce: 4.0 minimum, 8.x+ recommended  
- PHP: 7.4 minimum, 8.1+ recommended

### Security Implementation
- Input sanitization for all user inputs using WordPress functions
- Capability checks for product editing permissions
- Nonce verification for form submissions
- Data escaping for all output

### Implemented Validation
- ✅ SKU Guid required when "iLok Licensed" is enabled
- ✅ Non-empty string validation with trim()
- ✅ Maximum length: 255 characters enforced
- ✅ Server-side validation with error display
- ✅ Validation prevents saving when rules violated

### Current UI/UX Implementation
- ✅ WooCommerce checkbox integration using `woocommerce_wp_checkbox()`
- ✅ Progressive disclosure pattern with JavaScript
- ✅ Tab integration using WooCommerce data panels
- ✅ Error messages displayed via admin notices
- ⏳ Interface styling (uses WooCommerce defaults, custom styling pending)

## Development Commands

### Testing the Plugin
```bash
# No specific build commands - WordPress plugin with direct PHP execution
# Test by:
# 1. Activating plugin in WordPress admin
# 2. Creating/editing WooCommerce products
# 3. Checking iLok Licensed checkbox and filling SKU Guid
# 4. Processing test orders to verify metadata copying
```

### File Structure for Development
- Edit product admin features in `includes/class-woo-ilok-product-admin.php`
- Edit order processing in `includes/class-woo-ilok-order-handler.php`
- Edit JavaScript behavior in `assets/js/admin-product.js`
- Main plugin configuration in `woo-ilok-products.php`

## Testing Priorities
- ✅ Metadata correctly saves to products 
- ✅ Metadata copies to order items during checkout
- ⏳ Test with all WooCommerce product types
- ⏳ Ensure metadata accessible via standard WooCommerce functions
- ✅ No customer-facing interface changes (admin-only)
- ⏳ Compatibility across WordPress/WooCommerce versions

## Critical Constraints
- ✅ No customer-facing functionality implemented
- ✅ Single SKU Guid per product (no multi-license support)
- ✅ Metadata-only approach (no automatic license fulfillment)
- ✅ Maintains backward compatibility with existing WooCommerce workflows