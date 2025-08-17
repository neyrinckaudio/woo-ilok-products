# WooCommerce iLok Products Plugin - Task List

## High Priority Tasks

### 1. Set up plugin structure and main plugin file
- Create main plugin file with proper headers
- Set up directory structure
- Define plugin constants and basic initialization

### 2. Create plugin activation/deactivation hooks
- Implement activation hook for setup
- Implement deactivation hook for cleanup
- Add any necessary database setup

### 3. Add 'iLok Licensed' checkbox to Product Data metabox header
- Hook into WooCommerce product data metabox
- Add checkbox alongside existing 'Virtual' and 'Downloadable' checkboxes
- Ensure compatibility with all product types

### 4. Create 'iLok Licensing' tab in product data panels
- Add new tab to product data interface
- Hook into `woocommerce_product_data_panels`
- Set up tab content structure

### 5. Add JavaScript for showing/hiding iLok tab based on checkbox state
- Create admin JavaScript file
- Implement dynamic show/hide functionality
- Ensure proper loading on product edit pages

### 6. Create SKU Guid field in iLok Licensing tab
- Add text input field for SKU Guid
- Set proper field attributes and labels
- Implement 255 character limit

### 7. Implement server-side validation for SKU Guid field
- Validate SKU Guid is required when iLok Licensed is checked
- Ensure non-empty string validation
- Add validation error messages

### 8. Save product metadata (ilok_licensed and ilok_sku_guid) on product save
- Hook into `woocommerce_process_product_meta`
- Save metadata with proper keys
- Handle checkbox and text field data

### 9. Copy iLok metadata to order item meta during checkout
- Hook into `woocommerce_checkout_create_order_line_item`
- Copy product metadata to order item metadata
- Maintain consistent metadata keys

## Medium Priority Tasks

### 10. Add proper input sanitization and data escaping
- Sanitize all user inputs
- Escape output data properly
- Follow WordPress security best practices

### 11. Implement nonce verification for form submissions
- Add nonce fields to forms
- Verify nonces on form processing
- Ensure CSRF protection

### 12. Add capability checks for product editing permissions
- Check user capabilities before processing
- Ensure only authorized users can edit
- Follow WooCommerce permission patterns

### 13. Style interface elements to match WooCommerce admin UI
- Apply consistent styling
- Match WooCommerce admin patterns
- Ensure visual integration

### 14. Test plugin with all WooCommerce product types (Simple, Variable, Grouped, External)
- Test Simple products
- Test Variable products
- Test Grouped products
- Test External/Affiliate products

### 15. Test WordPress/WooCommerce version compatibility
- Test minimum WordPress 5.0
- Test minimum WooCommerce 4.0
- Test with latest versions
- Verify PHP 7.4+ compatibility

### 16. Verify metadata is accessible via standard WooCommerce functions
- Test metadata retrieval from products
- Test metadata retrieval from order items
- Verify API compatibility for other plugins

### 17. Test order processing workflow with iLok-licensed products
- Test complete purchase flow
- Verify metadata copying
- Ensure no customer-facing changes

## Low Priority Tasks

### 18. Add plugin uninstall cleanup functionality
- Create uninstall hook
- Clean up plugin metadata
- Remove any plugin-specific data

### 19. Test for conflicts with popular WooCommerce extensions
- Test with common plugins
- Verify no hook conflicts
- Ensure stable operation

### 20. Verify responsive design works on mobile devices
- Test admin interface on mobile
- Ensure usability on small screens
- Verify touch interaction compatibility

## Acceptance Criteria Checklist

### Product Configuration
- [ ] "iLok Licensed" checkbox appears in Product Data header
- [ ] Checkbox works for all product types
- [ ] "iLok Licensing" tab appears/disappears based on checkbox state
- [ ] "SKU Guid" field accepts and saves text input
- [ ] Validation prevents saving when SKU Guid is empty and iLok Licensed is checked

### Data Handling
- [ ] Product metadata saved correctly with proper keys
- [ ] Order item metadata populated on purchase
- [ ] Metadata accessible via standard WooCommerce functions
- [ ] No data corruption or loss during order processing

### User Interface
- [ ] Interface elements match WooCommerce styling
- [ ] No JavaScript errors in browser console
- [ ] Responsive design works on mobile devices
- [ ] Clear validation messages displayed to users

### Compatibility
- [ ] Plugin activates without PHP errors
- [ ] No conflicts with popular WooCommerce extensions
- [ ] Works across supported WordPress/WooCommerce versions
- [ ] Proper uninstall cleanup