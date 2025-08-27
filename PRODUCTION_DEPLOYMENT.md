# Church Admin System - Production Deployment Guide

## Overview
This guide provides comprehensive instructions for deploying the Church Admin System to a production environment with enhanced security, performance, and reliability.

## Pre-Deployment Checklist

### 1. Environment Setup
- [ ] Copy `.env.example` to `.env` and configure all variables
- [ ] Set `ENVIRONMENT=production` in `.env`
- [ ] Configure database credentials
- [ ] Set up SSL certificate (HTTPS)
- [ ] Configure email settings for notifications

### 2. Security Configuration
- [ ] Change default admin credentials immediately after first login
- [ ] Review and update database passwords
- [ ] Enable HTTPS redirects in `.htaccess` (uncomment HTTPS redirect rules)
- [ ] Configure firewall rules
- [ ] Set up fail2ban for additional protection
- [ ] Review file permissions (see File Permissions section)

### 3. Database Setup
- [ ] Create production database
- [ ] Import initial schema (handled automatically by the system)
- [ ] Configure database backups
- [ ] Set up database user with minimal required privileges

### 4. File Permissions
Set appropriate file permissions for security:

```bash
# Set directory permissions
find /path/to/church-admin -type d -exec chmod 755 {} \;

# Set file permissions
find /path/to/church-admin -type f -exec chmod 644 {} \;

# Make uploads directory writable
chmod 755 uploads/
chmod 755 uploads/volunteers/

# Protect sensitive files
chmod 600 .env
chmod 600 admin/config.php
chmod 600 admin/database.php

# Create logs directory with proper permissions
mkdir -p admin/logs
chmod 755 admin/logs
```

### 5. Server Configuration

#### Apache Configuration
Ensure the following modules are enabled:
- mod_rewrite
- mod_headers
- mod_deflate
- mod_expires
- mod_ssl (for HTTPS)

#### PHP Configuration
Recommended PHP settings for production:

```ini
; Security
expose_php = Off
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /path/to/logs/php_errors.log

; Performance
memory_limit = 256M
max_execution_time = 30
max_input_time = 60
post_max_size = 20M
upload_max_filesize = 10M

; Security
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
session.cookie_samesite = "Strict"
```

## Post-Deployment Steps

### 1. Initial Admin Setup
1. Access the admin panel at `/admin/`
2. Log in with default credentials (admin/admin123)
3. **IMMEDIATELY** change the default password
4. Create additional admin users as needed
5. Disable or remove the default admin account

### 2. System Verification
- [ ] Test login functionality
- [ ] Verify CSRF protection is working
- [ ] Test rate limiting (try multiple failed logins)
- [ ] Check error logging
- [ ] Verify file upload functionality
- [ ] Test email notifications (if configured)

### 3. Monitoring Setup
- [ ] Set up log monitoring
- [ ] Configure database backup verification
- [ ] Set up uptime monitoring
- [ ] Configure security alerts

## Security Features Implemented

### Authentication & Authorization
- ✅ Secure password hashing (PHP password_hash)
- ✅ Session timeout management
- ✅ Rate limiting for login attempts
- ✅ Account lockout after failed attempts
- ✅ CSRF protection on all forms
- ✅ Input sanitization and validation

### Data Protection
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection (output escaping)
- ✅ File upload restrictions
- ✅ Directory traversal prevention
- ✅ Sensitive file protection via .htaccess

### Infrastructure Security
- ✅ Security headers (X-Frame-Options, CSP, etc.)
- ✅ HTTPS enforcement (when enabled)
- ✅ Server signature hiding
- ✅ Directory browsing disabled
- ✅ Error page customization

### Logging & Monitoring
- ✅ Security event logging
- ✅ Failed login attempt tracking
- ✅ Administrative action logging
- ✅ Error logging with proper levels

## Performance Optimizations

### Caching
- ✅ Static file caching via .htaccess
- ✅ Browser caching headers
- ✅ Gzip compression enabled

### Database
- ✅ Proper indexing on frequently queried columns
- ✅ Optimized queries with prepared statements
- ✅ Connection pooling via PDO

## Backup Strategy

### Automated Backups
Set up automated backups for:
1. **Database**: Daily full backups, hourly incremental
2. **Files**: Daily backup of uploads and configuration
3. **Logs**: Weekly archive of log files

### Backup Script Example
```bash
#!/bin/bash
# Daily backup script

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/church-admin"
DB_NAME="kazrxdvk_church_management"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u username -p password $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /path/to/church-admin/uploads

# Clean old backups (keep 30 days)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

## Maintenance Tasks

### Daily
- [ ] Review security logs
- [ ] Check system error logs
- [ ] Verify backup completion

### Weekly
- [ ] Update system packages
- [ ] Review user access logs
- [ ] Database optimization
- [ ] Performance monitoring review

### Monthly
- [ ] Security audit
- [ ] Password policy review
- [ ] Backup restoration test
- [ ] System performance analysis

## Troubleshooting

### Common Issues

#### Login Issues
1. Check if account is locked: Review admin_logs table
2. Verify CSRF tokens are being generated
3. Check session configuration
4. Review rate limiting logs

#### Database Connection Issues
1. Verify database credentials in .env
2. Check database server status
3. Review database user permissions
4. Check connection limits

#### File Upload Issues
1. Verify directory permissions (uploads/)
2. Check PHP upload limits
3. Review .htaccess file restrictions
4. Verify disk space

### Log Locations
- **Security Logs**: `admin/logs/admin.log`
- **PHP Errors**: Server-configured location
- **Apache Errors**: `/var/log/apache2/error.log` (typical)
- **Access Logs**: `/var/log/apache2/access.log` (typical)

## Support & Updates

### Getting Help
- Review logs first for error details
- Check this documentation for common solutions
- Contact: Onpoint Softwares Solutions

### Updates
- Always test updates in a staging environment first
- Backup database and files before applying updates
- Review changelog for breaking changes
- Update .htaccess if security rules change

## Security Contacts

For security-related issues or vulnerabilities:
- **Email**: security@onpointsoft.com
- **Response Time**: 24-48 hours for critical issues

---

**Last Updated**: <?= date('Y-m-d') ?>

**Version**: 2.0.0
