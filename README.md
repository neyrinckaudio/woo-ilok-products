# WooCommerce iLok Products

A WordPress plugin that extends WooCommerce to support iLok licensing metadata for digital audio software products. This plugin adds product configuration options for iLok licensing, provides customer-facing iLok User ID validation during the shopping experience, and stores metadata that can be accessed by other plugins for license fulfillment. It works well as a companion to the WooCommerce iLok Orders plugin.

## Features

- **iLok Licensed Product Configuration**: Checkbox in Product Data metabox to mark products as iLok-licensed
- **Progressive Disclosure Interface**: iLok Licensing tab appears only when product is marked as iLok-licensed
- **SKU GUID Management**: Field for entering iLok SKU GUID with 255 character limit and validation
- **Customer iLok User ID Validation**: Customer-facing field on product pages for iLok User ID entry
- **Real-time Validation**: AJAX validation with wp-edenremote plugin integration
- **Order Metadata Storage**: Automatic copying of iLok metadata to order items during checkout
- **Professional UI**: Styled to match WooCommerce design patterns with responsive layout

## Requirements

- **WordPress**: 5.0 minimum, 6.x+ recommended
- **WooCommerce**: 4.0 minimum, 8.x+ recommended  
- **PHP**: 7.4 minimum, 8.1+ recommended

## Installation

1. Upload the plugin files to `/wp-content/plugins/woo-ilok-products/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure products by editing them and checking the "iLok Licensed" checkbox
4. Fill in the SKU GUID in the iLok Licensing tab

## Usage

### Product Configuration

1. Edit a WooCommerce product
2. In the Product Data section, check "iLok Licensed" 
3. The "iLok Licensing" tab will appear
4. Enter the SKU GUID for the product (required when iLok Licensed is enabled)
5. Save the product

### Customer Experience

When customers view an iLok-licensed product:
1. An iLok User ID field appears above the Add to Cart button
2. Customers must enter and validate their iLok User ID
3. Real-time validation occurs via AJAX (requires wp-edenremote plugin)
4. Products cannot be added to cart without a validated iLok User ID

### Order Processing

- iLok metadata is automatically copied to order items during checkout
- Both product metadata (`ilok_product`, `ilok_sku_guid`) and customer data (`iLok User ID`) are stored
- Metadata is accessible via standard WooCommerce functions for license fulfillment

## Metadata Schema

### Product Meta
- `ilok_product`: Boolean - Whether product requires iLok licensing
- `ilok_sku_guid`: String - iLok SKU GUID (max 255 characters)

### Order Item Meta
- `ilok_product`: Boolean - Copied from product
- `ilok_sku_guid`: String - Copied from product  
- `iLok User ID`: String - Customer's validated iLok User ID (max 32 characters)

## Integration

The plugin integrates with the **wp-edenremote** plugin for iLok User ID validation. If wp-edenremote is not available, the plugin falls back to basic format validation.

## Development

### File Structure
```
woo-ilok-products/
├── woo-ilok-products.php              # Main plugin file
├── includes/
│   ├── class-woo-ilok-product-admin.php    # Product admin interface
│   ├── class-woo-ilok-order-handler.php    # Order processing
│   └── class-woo-ilok-frontend.php         # Customer-facing functionality
├── assets/
│   ├── js/
│   │   ├── admin-product.js           # Progressive disclosure JavaScript
│   │   └── frontend-validation.js    # iLok User ID validation
│   └── css/
│       └── frontend.css               # Customer-facing styles
└── languages/                         # Translation files
```

### Testing

Test the plugin by:
1. Creating/editing WooCommerce products
2. Enabling iLok licensing and entering SKU GUIDs
3. Testing customer-facing iLok User ID validation
4. Processing test orders to verify metadata storage

## License

This project is licensed under the MIT License - see below for details:

```
MIT License

Copyright (c) 2024

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes following WordPress coding standards
4. Test your changes thoroughly
5. Submit a pull request

## Contact

Paul Neyrinck
www.neyrinck.com