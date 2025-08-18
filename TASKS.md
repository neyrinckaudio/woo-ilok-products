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

### 10. Add iLok User ID field and validation to product pages
- Display iLok User ID text field on product pages for iLok-licensed products
- Add "Validate" button next to the field
- Position above Add To Cart section
- Implement 32 character limit validation

### 11. Implement iLok User ID validation with wp-edenremote plugin
- Integrate with wp-edenremote plugin API for validation
- Validate that iLok User ID is non-empty string with no spaces
- Show validation feedback to customer
- Only allow add to cart after successful validation

### 12. Prevent add to cart without valid iLok User ID
- Hook into add to cart process
- Block cart addition for iLok-licensed products without validated User ID
- Display appropriate error messages to customers
- Store validated iLok User ID in cart/session

### 13. Store iLok User ID in order item metadata
- Save iLok User ID to order item meta during checkout
- Use metadata key: 'iLok User ID'
- Ensure data persists through order completion
- Make accessible via standard WooCommerce functions

## Medium Priority Tasks

### 14. Add proper input sanitization and data escaping
- Sanitize all user inputs
- Escape output data properly
- Follow WordPress security best practices

### 15. Implement nonce verification for form submissions
- Add nonce fields to forms
- Verify nonces on form processing
- Ensure CSRF protection

### 16. Add capability checks for product editing permissions
- Check user capabilities before processing
- Ensure only authorized users can edit
- Follow WooCommerce permission patterns

### 17. Style interface elements to match WooCommerce admin UI
- Apply consistent styling
- Match WooCommerce admin patterns
- Ensure visual integration

### 18. Test plugin with all WooCommerce product types (Simple, Variable, Grouped, External)
- Test Simple products
- Test Variable products
- Test Grouped products
- Test External/Affiliate products

### 19. Test WordPress/WooCommerce version compatibility
- Test minimum WordPress 5.0
- Test minimum WooCommerce 4.0
- Test with latest versions
- Verify PHP 7.4+ compatibility

### 20. Verify metadata is accessible via standard WooCommerce functions
- Test metadata retrieval from products
- Test metadata retrieval from order items
- Verify API compatibility for other plugins

### 21. Test order processing workflow with iLok-licensed products
- Test complete purchase flow
- Verify metadata copying
- Ensure no customer-facing changes

## Low Priority Tasks

### 22. Add plugin uninstall cleanup functionality
- Create uninstall hook
- Clean up plugin metadata
- Remove any plugin-specific data

### 23. Test for conflicts with popular WooCommerce extensions
- Test with common plugins
- Verify no hook conflicts
- Ensure stable operation

### 24. Verify responsive design works on mobile devices
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

### Shop Add To Cart
- [ ] "iLok User ID" text field and "Validate" button appears above Add To Cart section if the product is iLok licensed
- [ ] Validation prevents adding product to cart without valid iLok User ID
- [ ] Integration with wp-edenremote plugin API works correctly
- [ ] Error messages displayed for invalid or missing iLok User ID
- [ ] 32 character limit enforced on iLok User ID field

### Data Handling
- [ ] Product metadata saved correctly with proper keys
- [ ] Order item metadata populated on purchase (including iLok User ID)
- [ ] Metadata accessible via standard WooCommerce functions
- [ ] No data corruption or loss during order processing
- [ ] iLok User ID properly stored in order item meta with key 'iLok User ID'

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