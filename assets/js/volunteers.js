/**
 * Volunteer Portal JavaScript - Production Ready
 * 
 * Secure client-side functionality for the volunteer portal
 * with enhanced error handling and user experience.
 * 
 * @version 2.0.0
 * @author Onpoint Softwares Solutions
 */

'use strict';

// Global application state
const VolunteerApp = {
    currentUser: null,
    currentSection: 'dashboard',
    isLoading: false,
    csrfToken: null,
    
    // Initialize the application
    init() {
        this.csrfToken = document.querySelector('input[name="csrf_token"]')?.value;
        this.bindEvents();
        this.checkSession();
        this.setupFormValidation();
    },
    
    // Bind event listeners
    bindEvents() {
        // Login form
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', this.handleLogin.bind(this));
        }
        
        // Logout
        window.logout = this.handleLogout.bind(this);
        
        // Navigation
        window.showSection = this.showSection.bind(this);
        window.toggleSidebar = this.toggleSidebar.bind(this);
        
        // Mobile responsiveness
        window.addEventListener('resize', this.handleResize.bind(this));
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', this.handleOutsideClick.bind(this));
        
        // Form submissions
        this.bindFormSubmissions();
    },
    
    // Check if user is already logged in
    checkSession() {
        const isLoggedIn = document.getElementById('dashboard-page').style.display !== 'none';
        if (isLoggedIn) {
            this.showDashboard();
        }
    },
    
    // Handle login form submission
    async handleLogin(e) {
        e.preventDefault();
        
        if (this.isLoading) return;
        
        const form = e.target;
        const formData = new FormData(form);
        const email = formData.get('email').trim();
        const password = formData.get('password');
        
        // Client-side validation
        if (!this.validateEmail(email)) {
            this.showAlert('Please enter a valid email address.', 'danger');
            return;
        }
        
        if (password.length < 6) {
            this.showAlert('Password must be at least 6 characters long.', 'danger');
            return;
        }
        
        this.setLoading(true);
        
        try {
            const response = await this.makeRequest('volunteers_api.php', {
                action: 'login',
                email: email,
                password: password,
                csrf_token: this.csrfToken
            });
            
            if (response.success) {
                this.currentUser = response.user;
                this.showDashboard();
                this.updateUserInfo();
                this.showAlert('Welcome back! Login successful.', 'success');
            } else {
                this.showAlert(response.message || 'Login failed. Please try again.', 'danger');
                
                // Handle rate limiting
                if (response.locked) {
                    this.disableLoginForm(true);
                }
            }
        } catch (error) {
            console.error('Login error:', error);
            this.showAlert('An error occurred. Please try again later.', 'danger');
        } finally {
            this.setLoading(false);
        }
    },
    
    // Handle logout
    async handleLogout() {
        if (!confirm('Are you sure you want to logout?')) {
            return;
        }
        
        try {
            await this.makeRequest('volunteers_api.php', {
                action: 'logout',
                csrf_token: this.csrfToken
            });
        } catch (error) {
            console.error('Logout error:', error);
        }
        
        this.currentUser = null;
        this.showLogin();
        this.showAlert('You have been logged out successfully.', 'info');
    },
    
    // Show dashboard
    showDashboard() {
        document.getElementById('login-page').style.display = 'none';
        document.getElementById('dashboard-page').style.display = 'block';
        this.showSection('dashboard');
    },
    
    // Show login page
    showLogin() {
        document.getElementById('login-page').style.display = 'flex';
        document.getElementById('dashboard-page').style.display = 'none';
        this.clearAlerts();
        document.getElementById('login-form').reset();
    },
    
    // Update user information in UI
    updateUserInfo() {
        if (!this.currentUser) return;
        
        const elements = {
            'user-name': this.currentUser.name,
            'user-display-name': this.currentUser.name,
            'user-role': this.currentUser.role,
            'user-avatar': this.currentUser.avatar
        };
        
        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        });
    },
    
    // Show specific content section
    showSection(sectionName) {
        // Update navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        const activeLink = document.querySelector(`[onclick="showSection('${sectionName}')"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
        
        // Show content section
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active');
        });
        
        const targetSection = document.getElementById(`${sectionName}-content`);
        if (targetSection) {
            targetSection.classList.add('active');
        }
        
        this.currentSection = sectionName;
        
        // Close sidebar on mobile after navigation
        if (window.innerWidth < 992) {
            this.toggleSidebar(false);
        }
    },
    
    // Toggle sidebar
    toggleSidebar(force) {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (typeof force === 'boolean') {
            sidebar.classList.toggle('active', force);
            mainContent.classList.toggle('sidebar-open', force);
        } else {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-open');
        }
    },
    
    // Handle window resize
    handleResize() {
        if (window.innerWidth >= 992) {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar && mainContent) {
                sidebar.classList.remove('active');
                mainContent.classList.remove('sidebar-open');
            }
        }
    },
    
    // Handle clicks outside sidebar on mobile
    handleOutsideClick(e) {
        if (window.innerWidth >= 992) return;
        
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.mobile-menu-btn');
        
        if (sidebar && sidebar.classList.contains('active') &&
            !sidebar.contains(e.target) &&
            !toggleBtn.contains(e.target)) {
            this.toggleSidebar(false);
        }
    },
    
    // Set loading state
    setLoading(loading) {
        this.isLoading = loading;
        const loginBtn = document.querySelector('#login-form button[type="submit"]');
        
        if (loginBtn) {
            const btnText = loginBtn.querySelector('.btn-text');
            const spinner = loginBtn.querySelector('.loading-spinner');
            
            if (loading) {
                btnText.classList.add('d-none');
                spinner.classList.remove('d-none');
                loginBtn.disabled = true;
            } else {
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
                loginBtn.disabled = false;
            }
        }
    },
    
    // Disable login form (for rate limiting)
    disableLoginForm(disabled) {
        const form = document.getElementById('login-form');
        const inputs = form.querySelectorAll('input, button');
        
        inputs.forEach(input => {
            input.disabled = disabled;
        });
        
        if (disabled) {
            setTimeout(() => {
                this.disableLoginForm(false);
                this.showAlert('You can now try logging in again.', 'info');
            }, 15 * 60 * 1000); // 15 minutes
        }
    },
    
    // Show alert message
    showAlert(message, type = 'info') {
        const alertsContainer = document.getElementById('login-alerts') || 
                              document.querySelector('.content-section.active .alerts') ||
                              document.querySelector('.content-section.active');
        
        if (!alertsContainer) return;
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${this.getAlertIcon(type)} me-2"></i>
            ${this.escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Remove existing alerts
        alertsContainer.querySelectorAll('.alert').forEach(alert => alert.remove());
        
        alertsContainer.insertBefore(alertDiv, alertsContainer.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    },
    
    // Clear all alerts
    clearAlerts() {
        document.querySelectorAll('.alert').forEach(alert => alert.remove());
    },
    
    // Get alert icon based on type
    getAlertIcon(type) {
        const icons = {
            success: 'check-circle',
            danger: 'exclamation-triangle',
            warning: 'exclamation-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    },
    
    // Validate email format
    validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    // Escape HTML to prevent XSS
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },
    
    // Make secure AJAX request
    async makeRequest(url, data) {
        const formData = new FormData();
        
        Object.entries(data).forEach(([key, value]) => {
            formData.append(key, value);
        });
        
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        return result;
    },
    
    // Setup form validation
    setupFormValidation() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
            
            // Real-time validation
            form.querySelectorAll('input[required]').forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
                
                input.addEventListener('input', () => {
                    if (input.classList.contains('is-invalid')) {
                        this.validateField(input);
                    }
                });
            });
        });
    },
    
    // Validate form
    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    },
    
    // Validate individual field
    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';
        
        // Required validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = 'This field is required.';
        }
        
        // Email validation
        if (field.type === 'email' && value && !this.validateEmail(value)) {
            isValid = false;
            message = 'Please enter a valid email address.';
        }
        
        // Password validation
        if (field.type === 'password' && value && value.length < 6) {
            isValid = false;
            message = 'Password must be at least 6 characters long.';
        }
        
        // Update field appearance
        field.classList.toggle('is-invalid', !isValid);
        field.classList.toggle('is-valid', isValid && value);
        
        // Show/hide error message
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!isValid && message) {
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                field.parentNode.appendChild(feedback);
            }
            feedback.textContent = message;
        } else if (feedback) {
            feedback.remove();
        }
        
        return isValid;
    },
    
    // Bind form submissions for various features
    bindFormSubmissions() {
        // File upload forms
        document.querySelectorAll('input[type="file"]').forEach(input => {
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', this.handleFileUpload.bind(this));
            }
        });
        
        // Message forms (prayer requests, suggestions)
        document.querySelectorAll('form[data-action="submit_message"]').forEach(form => {
            form.addEventListener('submit', this.handleMessageSubmit.bind(this));
        });
    },
    
    // Handle file upload
    async handleFileUpload(e) {
        e.preventDefault();
        
        const form = e.target;
        const fileInput = form.querySelector('input[type="file"]');
        const file = fileInput.files[0];
        
        if (!file) {
            this.showAlert('Please select a file to upload.', 'warning');
            return;
        }
        
        // Validate file type and size
        if (file.type !== 'application/pdf') {
            this.showAlert('Only PDF files are allowed.', 'danger');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) { // 5MB
            this.showAlert('File size must be less than 5MB.', 'danger');
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'upload_commitment');
        formData.append('file', file);
        formData.append('csrf_token', this.csrfToken);
        
        try {
            const response = await this.makeRequest('volunteers_api.php', Object.fromEntries(formData));
            
            if (response.success) {
                this.showAlert(response.message, 'success');
                form.reset();
            } else {
                this.showAlert(response.message, 'danger');
            }
        } catch (error) {
            console.error('Upload error:', error);
            this.showAlert('Upload failed. Please try again.', 'danger');
        }
    },
    
    // Handle message submission
    async handleMessageSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const message = formData.get('message').trim();
        const messageType = formData.get('message_type') || 'suggestion';
        
        if (!message) {
            this.showAlert('Please enter your message.', 'warning');
            return;
        }
        
        try {
            const response = await this.makeRequest('volunteers_api.php', {
                action: 'submit_message',
                msg_type: messageType,
                message: message,
                csrf_token: this.csrfToken
            });
            
            if (response.success) {
                this.showAlert(response.message, 'success');
                form.reset();
            } else {
                this.showAlert(response.message, 'danger');
            }
        } catch (error) {
            console.error('Message submission error:', error);
            this.showAlert('Failed to submit message. Please try again.', 'danger');
        }
    }
};

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    VolunteerApp.init();
});

// Global functions for backward compatibility
window.showRegister = function() {
    alert('Registration feature coming soon! Please contact the church office for now.');
};

window.showForgotPassword = function() {
    alert('Password reset feature coming soon! Please contact the church office for assistance.');
};
