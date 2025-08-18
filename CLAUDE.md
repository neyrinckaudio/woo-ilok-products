# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress plugin called "woo-ilok-products" that extends WooCommerce to support iLok licensing metadata for digital audio software products. The plugin adds product configuration options for iLok licensing, provides customer-facing iLok User ID validation during the shopping experience, and stores metadata that can be accessed by other plugins for license fulfillment.

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
- **Customer-facing iLok User ID functionality**:
  - iLok User ID field and validation on product pages
  - Integration with wp-edenremote plugin for User ID validation
  - Add to cart prevention without valid User ID
  - iLok User ID storage in order item metadata
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
│   ├── class-woo-ilok-order-handler.php    # Order processing
│   └── class-woo-ilok-frontend.php         # Customer-facing functionality (planned)
├── assets/
│   ├── js/
│   │   ├── admin-product.js       # Progressive disclosure JavaScript
│   │   └── frontend-validation.js # iLok User ID validation (planned)
│   └── css/                       # Stylesheets (future)
└── languages/                     # Translation files
```

### Key Components
- **WooIlokProducts**: Main plugin class with dependency checking
- **WooIlokProductAdmin**: Handles product admin interface and validation
- **WooIlokOrderHandler**: Manages order processing and metadata copying
- **WooIlokFrontend**: Handles customer-facing iLok User ID validation (planned)
- **Progressive Disclosure**: JavaScript controls tab visibility based on checkbox
- **Frontend Validation**: JavaScript for iLok User ID validation on product pages (planned)

### Implemented Hooks
- `woocommerce_product_options_general_product_data` - Checkbox placement
- `woocommerce_product_data_tabs` - Tab registration
- `woocommerce_product_data_panels` - Tab content
- `woocommerce_process_product_meta` - Data saving and validation
- `woocommerce_checkout_create_order_line_item` - Order metadata copying
- `woocommerce_new_order_item` - Manual order support

### Planned Hooks (for iLok User ID functionality)
- `woocommerce_single_product_summary` - iLok User ID field display
- `woocommerce_add_to_cart_validation` - Prevent cart addition without valid User ID
- `woocommerce_add_cart_item_data` - Store User ID in cart item data
- `wp_enqueue_scripts` - Load frontend validation JavaScript

### Metadata Schema
```php
// Product Meta
'_ilok_licensed' => 'yes' | 'no'    // String values for WooCommerce compatibility
'_ilok_sku_guid' => 'string'        // SKU Guid value (max 255 chars)

// Order Item Meta (copied from product)
'_ilok_licensed' => 'yes' | 'no'
'_ilok_sku_guid' => 'string'

// Order Item Meta (entered by customer during add to cart)
'iLok User ID' => 'string'          // Customer's iLok User ID (max 32 chars, no spaces)
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

### Planned Validation (iLok User ID)
- ⏳ iLok User ID required to add iLok-licensed products to cart
- ⏳ Non-empty string validation with no spaces allowed
- ⏳ Maximum length: 32 characters enforced
- ⏳ Integration with wp-edenremote plugin for User ID validation
- ⏳ Client-side and server-side validation

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
# 4. Testing customer-facing iLok User ID validation on product pages
# 5. Testing add to cart prevention without valid User ID
# 6. Processing test orders to verify metadata copying
```

### File Structure for Development
- Edit product admin features in `includes/class-woo-ilok-product-admin.php`
- Edit order processing in `includes/class-woo-ilok-order-handler.php`
- Edit customer-facing features in `includes/class-woo-ilok-frontend.php` (planned)
- Edit JavaScript behavior in `assets/js/admin-product.js`
- Edit frontend validation in `assets/js/frontend-validation.js` (planned)
- Main plugin configuration in `woo-ilok-products.php`

## Testing Priorities
- ✅ Metadata correctly saves to products 
- ✅ Metadata copies to order items during checkout
- ⏳ Customer-facing iLok User ID validation functionality
- ⏳ Add to cart prevention without valid User ID
- ⏳ Integration with wp-edenremote plugin
- ⏳ iLok User ID storage in order item metadata
- ⏳ Test with all WooCommerce product types
- ⏳ Ensure metadata accessible via standard WooCommerce functions
- ⏳ Compatibility across WordPress/WooCommerce versions

## Critical Constraints
- ⏳ Customer-facing iLok User ID validation functionality (now planned)
- ✅ Single SKU Guid per product (no multi-license support)
- ⏳ Single iLok User ID per order item (no multi-user support)
- ✅ Metadata-only approach (no automatic license fulfillment)
- ✅ Maintains backward compatibility with existing WooCommerce workflows
- ⏳ Requires wp-edenremote plugin for User ID validation