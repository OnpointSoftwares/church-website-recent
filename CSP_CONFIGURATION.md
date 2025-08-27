# Content Security Policy (CSP) Configuration

## Current CSP Policy

The website uses a comprehensive Content Security Policy to enhance security while allowing necessary external resources.

### Complete CSP Directive

```html
<meta http-equiv="Content-Security-Policy" content="
    default-src 'self'; 
    script-src 'self' 'unsafe-inline' 'unsafe-eval' 
               https://cdn.jsdelivr.net 
               https://code.jquery.com 
               https://www.googletagmanager.com 
               https://www.google-analytics.com 
               https://cdnjs.cloudflare.com; 
    style-src 'self' 'unsafe-inline' 
              https://cdn.jsdelivr.net 
              https://fonts.googleapis.com 
              https://cdnjs.cloudflare.com; 
    font-src 'self' 
             https://fonts.gstatic.com 
             https://cdnjs.cloudflare.com 
             https://cdn.jsdelivr.net; 
    img-src 'self' data: https: blob:; 
    connect-src 'self' 
                https://www.google-analytics.com 
                https://analytics.google.com 
                https://fonts.googleapis.com 
                https://fonts.gstatic.com; 
    object-src 'none'; 
    base-uri 'self';
">
```

## Allowed Resources by Category

### Scripts (`script-src`)
- **'self'**: Local JavaScript files
- **'unsafe-inline'**: Inline scripts (required for some functionality)
- **'unsafe-eval'**: Dynamic code evaluation (required for some libraries)
- **cdn.jsdelivr.net**: Bootstrap and other CDN scripts
- **code.jquery.com**: jQuery library
- **www.googletagmanager.com**: Google Tag Manager
- **www.google-analytics.com**: Google Analytics
- **cdnjs.cloudflare.com**: CloudFlare CDN resources

### Stylesheets (`style-src`)
- **'self'**: Local CSS files
- **'unsafe-inline'**: Inline styles (required for dynamic styling)
- **cdn.jsdelivr.net**: Bootstrap CSS
- **fonts.googleapis.com**: Google Fonts CSS
- **cdnjs.cloudflare.com**: CloudFlare CDN styles

### Fonts (`font-src`)
- **'self'**: Local font files
- **fonts.gstatic.com**: Google Fonts files
- **cdnjs.cloudflare.com**: CloudFlare CDN fonts
- **cdn.jsdelivr.net**: JSDelivr CDN fonts

### Images (`img-src`)
- **'self'**: Local images
- **data:**: Data URLs (base64 images)
- **https:**: Any HTTPS image source
- **blob:**: Blob URLs (for dynamic images)

### Connections (`connect-src`)
- **'self'**: Local API endpoints
- **www.google-analytics.com**: Analytics data
- **analytics.google.com**: Analytics API
- **fonts.googleapis.com**: Font API
- **fonts.gstatic.com**: Font resources

### Security Directives
- **object-src 'none'**: Blocks plugins (Flash, etc.)
- **base-uri 'self'**: Restricts base URL changes

## Common CSP Violations and Solutions

### 1. Bootstrap CSS Blocked
**Error**: `Refused to load stylesheet from 'cdn.jsdelivr.net'`
**Solution**: Ensure `https://cdn.jsdelivr.net` is in `style-src`

### 2. jQuery Blocked
**Error**: `Refused to load script from 'code.jquery.com'`
**Solution**: Ensure `https://code.jquery.com` is in `script-src`

### 3. Google Fonts Blocked
**Error**: `Refused to load font from 'fonts.gstatic.com'`
**Solution**: Ensure both `https://fonts.googleapis.com` and `https://fonts.gstatic.com` are in appropriate directives

### 4. Google Analytics Blocked
**Error**: `Refused to load script from 'googletagmanager.com'`
**Solution**: Add Google Analytics domains to `script-src` and `connect-src`

## Testing CSP Configuration

### Browser Console
1. Open Developer Tools (F12)
2. Check Console tab for CSP violations
3. Look for "Refused to load" messages

### CSP Validator Tools
- [CSP Evaluator](https://csp-evaluator.withgoogle.com/)
- [Mozilla CSP Validator](https://addons.mozilla.org/en-US/firefox/addon/laboratory-by-mozilla/)

### Test Page
Use `/test-errors.html` to verify CSP configuration:
- Tests external resource loading
- Validates script execution
- Checks font and style loading

## Security Considerations

### Allowed Unsafe Directives
- **'unsafe-inline'**: Required for inline styles and scripts
- **'unsafe-eval'**: Required for some JavaScript libraries

### Recommendations
1. **Minimize 'unsafe-*' directives** when possible
2. **Use nonces or hashes** for inline scripts in production
3. **Regularly audit** allowed domains
4. **Monitor CSP reports** if reporting is enabled

## Maintenance

### Adding New External Resources
1. Identify the resource domain
2. Add to appropriate CSP directive
3. Test in development environment
4. Update this documentation

### Removing Unused Resources
1. Audit current external dependencies
2. Remove unused domains from CSP
3. Test thoroughly to ensure no breakage

## Production Deployment

### Environment-Specific CSP
Consider different CSP policies for:
- **Development**: More permissive for debugging
- **Staging**: Production-like with additional logging
- **Production**: Strict policy with minimal permissions

### CSP Reporting
For production, consider adding CSP reporting:
```html
report-uri /csp-report-endpoint;
report-to csp-endpoint;
```

## Troubleshooting

### Common Issues
1. **Mixed Content**: Ensure all resources use HTTPS
2. **Subdomain Issues**: Use wildcard domains if needed (*.example.com)
3. **Third-party Widgets**: May require additional domains

### Debug Steps
1. Check browser console for violations
2. Temporarily disable CSP to isolate issues
3. Add domains one by one to identify minimum requirements
4. Use CSP in report-only mode for testing

## CSP Configuration Locations

**IMPORTANT**: CSP is configured in multiple locations. All must be updated consistently:

1. **`.htaccess`** (Server-level, highest priority)
2. **`/admin/config.php`** (Admin section PHP header)
3. **`/includes/header.php`** (Meta tag fallback)
4. **`/test-errors.html`** (Testing page)

### Priority Order
1. **Apache .htaccess headers** (overrides everything)
2. **PHP header() function** (overrides meta tags)
3. **HTML meta tags** (fallback only)

## Related Files
- **`/.htaccess`**: Server-level CSP configuration (PRIMARY)
- **`/admin/config.php`**: Admin section CSP configuration
- **`/includes/header.php`**: Meta tag CSP configuration (fallback)
- **`/test-errors.html`**: CSP testing page
- **This file**: Documentation and maintenance guide

Last Updated: 2025-01-27
