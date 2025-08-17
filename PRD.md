# Product Requirements Document (PRD)
## WooCommerce iLok Products Plugin

### 1. Overview

**Plugin Name:** woo-ilok-products  
**Version:** 1.0.0  
**Purpose:** Extend WooCommerce product configuration to support iLok licensing metadata for digital audio software products.

### 2. Problem Statement

WooCommerce store owners selling digital audio software that requires iLok licensing need a way to:
- Configure products with iLok licensing information
- Store licensing metadata that can be accessed by other plugins
- Manage iLok-specific product data within the existing WooCommerce workflow

### 3. Target Users

**Primary Users:**
- WooCommerce store administrators
- Digital audio software vendors
- Plugin developers integrating with iLok licensing systems

**Secondary Users:**
- Other WordPress plugins that handle iLok license fulfillment

### 4. Core Features

#### 4.1 Product Configuration Interface

**Feature:** iLok Licensed Checkbox
- **Location:** Product Data metabox header, alongside existing 'Virtual' and 'Downloadable' checkboxes
- **Availability:** All WooCommerce product types (Simple, Variable, Grouped, External)
- **Functionality:** Toggle to enable/disable iLok licensing for the product
- **Default State:** Unchecked/disabled

**Feature:** iLok Licensing Tab
- **Visibility:** Only appears when "iLok Licensed" checkbox is enabled
- **Tab Name:** "iLok Licensing"
- **Location:** Among product data tabs (General, Inventory, Shipping, etc.)

#### 4.2 iLok Metadata Fields

**SKU Guid Field**
- **Field Type:** Text input
- **Label:** "SKU Guid"
- **Validation:** Required when iLok Licensed is enabled
- **Character Limit:** 255 characters
- **Metadata Key:** `ilok_sku_guid`
- **Storage:** WordPress post meta for the product

#### 4.3 Data Storage & Integration

**Product Meta Storage:**
- `ilok_licensed`: Boolean (1/0) - whether product uses iLok licensing
- `ilok_sku_guid`: String - the SKU Guid value

**Order Item Meta Storage:**
- When an iLok-licensed product is purchased, copy relevant metadata to order item meta
- Metadata keys maintained for consistency with product meta
- Available for other plugins via standard WooCommerce order item meta functions

### 5. Technical Requirements

#### 5.1 WordPress/WooCommerce Compatibility
- **WordPress:** 5.0 minimum, 6.x+ recommended
- **WooCommerce:** 4.0 minimum, 8.x+ recommended
- **PHP:** 7.4 minimum, 8.1+ recommended

#### 5.2 Plugin Architecture
- **Structure:** Object-oriented PHP
- **Hooks:** WordPress/WooCommerce action and filter hooks
- **Data Handling:** WordPress metadata APIs
- **Validation:** Server-side validation with JavaScript enhancement

#### 5.3 Database Impact
- Uses existing WordPress `postmeta` and WooCommerce order item meta tables
- No custom database tables required
- Minimal performance impact

### 6. User Experience Requirements

#### 6.1 Admin Interface
- Seamless integration with existing WooCommerce product interface
- Progressive disclosure: iLok tab only shows when needed
- Clear field labeling and validation messages
- Consistent with WooCommerce admin UI/UX patterns

#### 6.2 Customer-Facing Experience
- **Checkout:** No changes to customer checkout experience
- **Order Details:** iLok metadata not visible to customers
- **Emails:** No modifications to order confirmation emails
- **Account Pages:** No changes to customer account/order history pages

### 7. Functional Specifications

#### 7.1 Product Admin Workflow
1. Administrator edits product in WP Admin
2. Checks "iLok Licensed" checkbox in Product Data header
3. "iLok Licensing" tab becomes available
4. Administrator enters required "SKU Guid" in the tab
5. Saves product - validation ensures SKU Guid is provided
6. Product meta is stored: `ilok_licensed` and `ilok_sku_guid`

#### 7.2 Order Processing Workflow
1. Customer purchases iLok-licensed product
2. During order processing, plugin detects iLok-licensed items
3. Copies product iLok metadata to order item metadata
4. Order item meta available for other plugins via WooCommerce APIs
5. No customer-visible changes to order completion

#### 7.3 Integration Points
- **Product Save:** Hook into `woocommerce_process_product_meta`
- **Order Processing:** Hook into `woocommerce_checkout_create_order_line_item`
- **Admin Interface:** Use `woocommerce_product_data_panels` and related hooks

### 8. Data Specifications

#### 8.1 Metadata Structure
```php
// Product Meta
'ilok_licensed' => '1' | '0'        // Boolean stored as string
'ilok_sku_guid' => 'string'         // SKU Guid value

// Order Item Meta (copied from product)
'ilok_licensed' => '1' | '0'
'ilok_sku_guid' => 'string'
```

#### 8.2 Validation Rules
- SKU Guid is required when iLok Licensed is enabled
- SKU Guid must be non-empty string when provided
- Maximum length: 255 characters

### 9. Security Considerations

- Input sanitization for all user inputs
- Capability checks for product editing permissions
- Nonce verification for form submissions
- Data escaping for output

### 10. Performance Considerations

- Minimal database queries (uses existing meta systems)
- JavaScript only loaded on product edit pages
- No frontend performance impact
- Efficient hooks to avoid unnecessary processing

### 11. Success Criteria

**Primary Success Metrics:**
- Plugin installs and activates without conflicts
- iLok metadata successfully saved to products
- Order item metadata correctly populated on purchase
- No customer-facing interface changes

**Integration Success:**
- Other plugins can reliably access iLok metadata via standard WooCommerce functions
- Metadata structure supports common iLok licensing workflows

### 12. Future Considerations

**Potential Enhancements (Out of Scope for v1.0):**
- Multiple SKU Guid support for bundle products
- Additional iLok metadata fields
- Customer-facing license information display
- Integration with specific iLok fulfillment services
- Variable product support for different license types

### 13. Constraints & Limitations

- No customer-facing functionality in initial version
- Single SKU Guid per product (no multi-license support)
- No automatic license fulfillment (metadata only)
- Requires manual product configuration

### 14. Acceptance Criteria

#### 14.1 Product Configuration
- [ ] "iLok Licensed" checkbox appears in Product Data header
- [ ] Checkbox works for all product types
- [ ] "iLok Licensing" tab appears/disappears based on checkbox state
- [ ] "SKU Guid" field accepts and saves text input
- [ ] Validation prevents saving when SKU Guid is empty and iLok Licensed is checked

#### 14.2 Data Handling
- [ ] Product metadata saved correctly with proper keys
- [ ] Order item metadata populated on purchase
- [ ] Metadata accessible via standard WooCommerce functions
- [ ] No data corruption or loss during order processing

#### 14.3 User Interface
- [ ] Interface elements match WooCommerce styling
- [ ] No JavaScript errors in browser console
- [ ] Responsive design works on mobile devices
- [ ] Clear validation messages displayed to users

#### 14.4 Compatibility
- [ ] Plugin activates without PHP errors
- [ ] No conflicts with popular WooCommerce extensions
- [ ] Works across supported WordPress/WooCommerce versions
- [ ] Proper uninstall cleanup