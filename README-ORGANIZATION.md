# TRIDENT Theme - Organized Structure

## Overview

The TRIDENT theme has been reorganized into a modular, maintainable structure with clear separation of concerns. This document outlines the new organization and how to work with it.

## Directory Structure

```
trident/
├── assets/                          # Static assets
│   ├── css/                        # Stylesheets
│   ├── js/                         # JavaScript files
│   └── images/                     # Images and media
├── includes/                       # PHP includes and modules
│   ├── core/                       # Core theme functionality
│   │   ├── theme-setup.php         # Theme initialization
│   │   ├── user-management.php     # User roles and access control
│   │   ├── security.php            # Security enhancements
│   │   └── routing.php             # Custom routing and templates
│   ├── database/                   # Database operations
│   │   ├── database.php            # Database table creation
│   │   ├── products.php            # Product CRUD operations
│   │   ├── orders.php              # Order CRUD operations
│   │   └── banners.php             # Banner CRUD operations
│   ├── admin/                      # Admin functionality
│   │   └── admin-ajax.php          # AJAX handlers for admin
│   ├── components/                 # Reusable components
│   │   └── color-picker.php        # Color picker component
│   └── frontend/                   # Frontend-specific functionality
├── template-parts/                 # Template parts and components
│   ├── admin/                      # Admin template parts
│   │   ├── add-product-popup.php   # Add product popup
│   │   ├── banner-modal.php        # Banner management modal
│   │   └── product-list.php        # Product list component
│   ├── frontend/                   # Frontend template parts
│   │   ├── hero-section.php        # Hero section component
│   │   ├── product-grid.php        # Product grid component
│   │   └── footer.php              # Footer component
│   └── common/                     # Common template parts
│       ├── header.php              # Header component
│       └── navigation.php          # Navigation component
├── page-templates/                 # Page templates
│   ├── admin/                      # Admin page templates
│   │   ├── trident-admin.php       # Main admin page
│   │   └── login.php               # Login page
│   └── frontend/                   # Frontend page templates
│       ├── homepage.php            # Homepage template
│       └── all-products.php        # Products page template
├── functions.php                   # Main functions file (legacy)
├── functions-new.php               # New organized functions file
├── style.css                       # Main stylesheet
├── index.php                       # Main template file
├── header.php                      # Header template
├── footer.php                      # Footer template
└── README-ORGANIZATION.md          # This file
```

## Module Organization

### 1. Core (`includes/core/`)

Contains the fundamental theme functionality:

- **theme-setup.php**: Theme initialization, hooks, and basic setup
- **user-management.php**: User roles, access control, and authentication
- **security.php**: Security enhancements and customizer options
- **routing.php**: Custom routing rules and template handling

### 2. Database (`includes/database/`)

Database operations organized by entity:

- **database.php**: Table creation and database setup
- **products.php**: Product CRUD operations and queries
- **orders.php**: Order CRUD operations and queries
- **banners.php**: Banner CRUD operations and queries

### 3. Admin (`includes/admin/`)

Admin-specific functionality:

- **admin-ajax.php**: AJAX handlers for admin operations

### 4. Components (`includes/components/`)

Reusable UI components:

- **color-picker.php**: Color picker popup component

### 5. Template Parts (`template-parts/`)

Reusable template components organized by context:

- **admin/**: Admin-specific template parts
- **frontend/**: Frontend-specific template parts
- **common/**: Shared template parts

## Key Benefits

### 1. **Modularity**
- Each module has a single responsibility
- Easy to find and modify specific functionality
- Reduced coupling between components

### 2. **Maintainability**
- Clear file organization makes it easy to locate code
- Separated concerns make debugging easier
- Consistent naming conventions

### 3. **Scalability**
- Easy to add new modules without affecting existing code
- Clear structure for new developers to understand
- Organized asset loading

### 4. **Reusability**
- Components can be easily reused across different pages
- Template parts can be included where needed
- Consistent functionality across the theme

## Migration Guide

### From Old Structure to New Structure

1. **Backup your current theme**
2. **Replace functions.php**: Use `functions-new.php` as your new `functions.php`
3. **Move existing files**: Organize existing functionality into the new structure
4. **Update includes**: Update any hardcoded file paths
5. **Test thoroughly**: Ensure all functionality works as expected

### File Migration Examples

**Old**: All AJAX handlers in `functions.php`
**New**: AJAX handlers in `includes/admin/admin-ajax.php`

**Old**: Database functions scattered throughout
**New**: Database functions organized by entity in `includes/database/`

**Old**: Color picker code inline in templates
**New**: Color picker as reusable component in `includes/components/color-picker.php`

## Usage Examples

### Including Template Parts

```php
// Include admin template part
get_template_part('template-parts/admin/add-product-popup');

// Include frontend template part
get_template_part('template-parts/frontend/hero-section');

// Include common template part
get_template_part('template-parts/common/header');
```

### Using Database Functions

```php
// Get all active products
$products = trident_get_products(array('status' => 'active'));

// Get single product
$product = trident_get_product($product_id);

// Insert new product
$product_id = trident_insert_product($product_data);
```

### Using Components

```php
// Render color picker component
trident_render_color_picker();
```

## Best Practices

### 1. **File Naming**
- Use descriptive, lowercase names with hyphens
- Prefix functions with `trident_` to avoid conflicts
- Use consistent naming conventions across modules

### 2. **Code Organization**
- Keep related functionality together
- Use clear separation between admin and frontend code
- Document complex functions and classes

### 3. **Security**
- Always validate and sanitize user input
- Use nonces for AJAX requests
- Check user permissions before sensitive operations

### 4. **Performance**
- Load assets conditionally based on page context
- Use WordPress hooks and filters appropriately
- Minimize database queries

## Troubleshooting

### Common Issues

1. **Functions not found**: Ensure all required files are included in `functions-new.php`
2. **AJAX errors**: Check nonce verification and user permissions
3. **Template not loading**: Verify file paths and template hierarchy
4. **Database errors**: Ensure tables are created and functions exist

### Debug Mode

Enable WordPress debug mode to see detailed error messages:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## Future Enhancements

### Planned Improvements

1. **Asset Management**: Implement proper asset versioning and minification
2. **Caching**: Add object caching for database queries
3. **API Endpoints**: Create REST API endpoints for frontend functionality
4. **Testing**: Add unit tests for critical functions
5. **Documentation**: Generate API documentation from code comments

### Extension Points

The modular structure makes it easy to extend functionality:

- Add new database entities by creating files in `includes/database/`
- Create new admin features in `includes/admin/`
- Add reusable components in `includes/components/`
- Create new template parts in `template-parts/`

## Support

For questions or issues with the theme organization:

1. Check this documentation first
2. Review the code comments in each file
3. Test functionality in a development environment
4. Create detailed bug reports with steps to reproduce

---

*This documentation is maintained as part of the TRIDENT theme development process.* 