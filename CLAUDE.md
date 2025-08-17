# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress plugin called "woo-ilok-products" that extends WooCommerce to support iLok licensing metadata for digital audio software products. The plugin adds product configuration options for iLok licensing and stores metadata that can be accessed by other plugins for license fulfillment.

## Core Architecture

### Plugin Structure
- Main plugin file should follow WordPress plugin header standards
- Object-oriented PHP architecture using WordPress/WooCommerce hooks
- Uses existing WordPress `postmeta` and WooCommerce order item meta tables (no custom tables)
- Minimal database impact through efficient use of WordPress metadata APIs

### Key Components
- **Product Admin Interface**: Adds "iLok Licensed" checkbox and "iLok Licensing" tab to WooCommerce product admin
- **Metadata Management**: Stores `ilok_licensed` (boolean) and `ilok_sku_guid` (string) in product meta
- **Order Processing**: Copies iLok metadata from products to order items during checkout
- **Progressive Disclosure**: iLok tab only appears when checkbox is enabled via JavaScript

### Critical Integration Points
- `woocommerce_process_product_meta` - Save product metadata
- `woocommerce_checkout_create_order_line_item` - Copy metadata to order items  
- `woocommerce_product_data_panels` - Add admin interface elements

### Metadata Schema
```php
// Product Meta
'ilok_licensed' => '1' | '0'        // Boolean stored as string
'ilok_sku_guid' => 'string'         // SKU Guid value (max 255 chars)

// Order Item Meta (copied from product)
'ilok_licensed' => '1' | '0'
'ilok_sku_guid' => 'string'
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

### Validation Rules
- SKU Guid is required when "iLok Licensed" is enabled
- SKU Guid must be non-empty string when provided
- Maximum length: 255 characters
- Server-side validation with JavaScript enhancement

### UI/UX Requirements
- Seamless integration with existing WooCommerce product interface
- Progressive disclosure pattern (iLok tab only shows when checkbox enabled)
- Interface elements must match WooCommerce admin styling
- No customer-facing changes (admin-only functionality)
- Support all WooCommerce product types: Simple, Variable, Grouped, External

## Testing Priorities
- Verify metadata correctly saves to products and copies to order items
- Test with all WooCommerce product types
- Ensure metadata is accessible via standard WooCommerce functions
- Validate no customer-facing interface changes occur
- Check compatibility across supported WordPress/WooCommerce versions

## Critical Constraints
- No customer-facing functionality in v1.0
- Single SKU Guid per product (no multi-license support)
- Metadata-only approach (no automatic license fulfillment)
- Must maintain backward compatibility with existing WooCommerce workflows