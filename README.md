# Trident WordPress Theme

A custom WordPress theme designed specifically for the TRIDENT tumbler e-commerce website with modern design and product catalog functionality.

## 🎯 Overview

The Trident theme is a complete WordPress theme solution that includes:

- **Modern, responsive design** with Tailwind CSS
- **Custom page templates** for homepage and product catalog
- **Seamless integration** with the Tumbler Seller plugin
- **Professional admin interface** with custom styling
- **SEO optimized** structure
- **Mobile-first** approach

## 📁 File Structure

```
trident/
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   ├── trident-all-products.css
│   │   └── trident-homepage.css
│   ├── js/
│   │   ├── admin.js
│   │   ├── main.js
│   │   ├── trident-all-products.js
│   │   └── trident-homepage.js
│   └── images/
├── template-parts/
│   └── trident/
│       ├── header.php
│       └── footer.php
├── functions.php
├── header.php
├── footer.php
├── index.php
├── page-homepage.php
├── page-all-products.php
├── style.css
├── theme.json
├── readme.txt
└── README.md
```

## 🚀 Features

### Core Features
- **Responsive Design**: Works perfectly on all devices
- **Tailwind CSS**: Modern utility-first CSS framework
- **Custom Page Templates**: Homepage and All Products pages
- **Plugin Integration**: Works seamlessly with Tumbler Seller plugin
- **SEO Optimized**: Clean, semantic HTML structure
- **Fast Loading**: Optimized assets and minimal dependencies

### Page Templates
1. **Homepage Template** (`page-homepage.php`)
   - Hero section with carousel
   - About section
   - Customize section with tumbler previews
   - Featured products grid
   - Newsletter signup

2. **All Products Template** (`page-all-products.php`)
   - Product catalog layout
   - Integration with Tumbler Seller shortcodes
   - Featured products section
   - Professional e-commerce design

### Admin Features
- **Custom Admin Styling**: Professional admin interface
- **Form Validation**: Client-side validation for forms
- **Image Upload**: WordPress Media Library integration
- **Color Pickers**: Custom color selection interface
- **Tooltips**: Helpful tooltips for better UX
- **Notifications**: Toast-style notifications

## 🛠️ Installation

1. **Upload Theme**
   ```bash
   # Copy the trident folder to wp-content/themes/
   cp -r trident/ wp-content/themes/
   ```

2. **Activate Theme**
   - Go to WordPress Admin → Appearance → Themes
   - Find "Trident" and click "Activate"

3. **Configure Settings**
   - Go to Appearance → Customize
   - Configure hero section, colors, and other options

4. **Create Pages**
   - Create a new page and select "Homepage" template
   - Create another page and select "All Products" template

## 📋 Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Plugins**: Tumbler Seller plugin (recommended)

## 🎨 Customization

### Colors
The theme uses a modern color palette that can be customized through the WordPress Customizer:

- Primary: `#667eea` (Purple)
- Secondary: `#764ba2` (Dark Purple)
- Accent: `#f093fb` (Pink)
- Success: `#48bb78` (Green)
- Warning: `#ed8936` (Orange)
- Error: `#f56565` (Red)

### Typography
- **System Font Stack**: Modern, readable fonts
- **Responsive Sizing**: Scales appropriately on all devices
- **Custom Font Support**: Easy to add custom fonts

### Layout
- **Content Width**: 1200px max-width
- **Wide Width**: 1400px for full-width content
- **Responsive Breakpoints**: Mobile-first approach

## 🔧 Development

### Adding Custom Styles
```css
/* Add to style.css or create new CSS file */
.my-custom-class {
    /* Your styles here */
}
```

### Adding Custom JavaScript
```javascript
// Add to main.js or create new JS file
jQuery(document).ready(function($) {
    // Your JavaScript here
});
```

### Custom Page Templates
1. Create a new PHP file in the theme root
2. Add the template header comment:
   ```php
   <?php
   /**
    * Template Name: My Custom Template
    */
   ```

## 📱 Responsive Design

The theme is built with a mobile-first approach:

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

## 🔌 Plugin Integration

### Tumbler Seller Plugin
The theme is specifically designed to work with the Tumbler Seller plugin:

- **Shortcode Support**: `[tumbler_product_view]` and `[tumbler_product_options]`
- **Custom Post Types**: Optimized for `tumbler_product` posts
- **Admin Integration**: Enhanced admin interface for the plugin

## 🚀 Performance

- **Optimized Assets**: Minified CSS and JS where possible
- **Lazy Loading**: Images load as needed
- **Caching Ready**: Compatible with caching plugins
- **CDN Support**: Tailwind CSS loaded from CDN

## 🔒 Security

- **WordPress Standards**: Follows WordPress coding standards
- **Sanitization**: All user inputs are properly sanitized
- **Nonce Verification**: AJAX requests use nonces
- **XSS Protection**: Output is properly escaped

## 📞 Support

For support and questions:
- Check the documentation in the theme folder
- Review the WordPress Codex for general WordPress questions
- Contact the development team for theme-specific issues

## 📄 License

This theme is licensed under the GPL v2 or later.

## 🗓️ Changelog

### Version 1.0.0
- Initial release
- Custom homepage template
- All products page template
- Integration with Tumbler Seller plugin
- Responsive design
- Tailwind CSS integration
- Professional admin interface

---

**Built with ❤️ for TRIDENT** 