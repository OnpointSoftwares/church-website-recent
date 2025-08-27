/**
 * Contact Form Handler - Christ Ekklesia Fellowship Chapel
 * 
 * Enhanced contact form functionality with validation, AJAX submission,
 * and user feedback for better user experience.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize contact form functionality
    initContactForms();
    initRealTimeValidation();
    
    /**
     * Initialize all contact forms on the page
     */
    function initContactForms() {
        const forms = document.querySelectorAll('#contactForm, .contact-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', handleFormSubmission);
            
            // Add loading states to submit buttons
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                submitBtn.dataset.originalText = submitBtn.textContent || submitBtn.value;
            }
        });
    }
    
    /**
     * Handle form submission with AJAX
     */
    function handleFormSubmission(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        const messagesDiv = form.querySelector('#formMessages, .form-messages') || createMessagesDiv(form);
        
        // Clear previous messages
        messagesDiv.innerHTML = '';
        messagesDiv.className = 'form-messages mt-3';
        
        // Validate form
        if (!validateForm(form)) {
            showFormMessage(messagesDiv, 'Please correct the errors below.', 'error');
            return;
        }
        
        // Show loading state
        setLoadingState(submitBtn, true);
        
        // Prepare form data
        const formData = new FormData(form);
        
        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('csrf_token', csrfToken.getAttribute('content'));
        }
        
        // Submit form
        submitContactForm(form, formData, submitBtn, messagesDiv);
    }
    
    /**
     * Submit contact form via AJAX
     */
    function submitContactForm(form, formData, submitBtn, messagesDiv) {
        const endpoint = form.action || '/contact-handler.php';
        
        fetch(endpoint, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            setLoadingState(submitBtn, false);
            
            if (data.status === 'success') {
                showFormMessage(messagesDiv, data.message || 'Thank you for your message! We\'ll get back to you soon.', 'success');
                form.reset();
                
                // Track successful form submission
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'form_submit', {
                        'form_name': 'contact_form',
                        'page_location': window.location.href
                    });
                }
                
                // Auto-scroll to success message
                messagesDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                
            } else {
                let errorMessage = data.message || 'An error occurred. Please try again.';
                
                if (data.errors && Array.isArray(data.errors)) {
                    errorMessage += '<ul class="mb-0 mt-2">';
                    data.errors.forEach(error => {
                        errorMessage += `<li>${escapeHtml(error)}</li>`;
                    });
                    errorMessage += '</ul>';
                }
                
                showFormMessage(messagesDiv, errorMessage, 'error');
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            setLoadingState(submitBtn, false);
            
            // Fallback: try to submit form normally
            if (error.message.includes('HTTP error')) {
                showFormMessage(messagesDiv, 'There was a problem submitting your message. Please try again or contact us directly.', 'error');
            } else {
                // Network error - show offline message
                showFormMessage(messagesDiv, 'It seems you\'re offline. Please check your connection and try again.', 'warning');
            }
        });
    }
    
    /**
     * Validate entire form
     */
    function validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input, textarea, select');
        
        fields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    /**
     * Validate individual field
     */
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        
        // Clear previous validation
        field.classList.remove('is-invalid', 'is-valid');
        hideFieldError(field);
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        }
        
        // Email validation
        else if (field.type === 'email' && value) {
            if (!isValidEmail(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address.';
            }
        }
        
        // Phone validation
        else if (field.type === 'tel' && value) {
            if (!isValidPhone(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number.';
            }
        }
        
        // Minimum length validation
        else if (field.hasAttribute('minlength') && value) {
            const minLength = parseInt(field.getAttribute('minlength'));
            if (value.length < minLength) {
                isValid = false;
                errorMessage = `Please enter at least ${minLength} characters.`;
            }
        }
        
        // Maximum length validation
        else if (field.hasAttribute('maxlength') && value) {
            const maxLength = parseInt(field.getAttribute('maxlength'));
            if (value.length > maxLength) {
                isValid = false;
                errorMessage = `Please enter no more than ${maxLength} characters.`;
            }
        }
        
        // Apply validation styling
        if (isValid && value) {
            field.classList.add('is-valid');
        } else if (!isValid) {
            field.classList.add('is-invalid');
            showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    /**
     * Initialize real-time validation
     */
    function initRealTimeValidation() {
        const fields = document.querySelectorAll('.contact-form input, .contact-form textarea, #contactForm input, #contactForm textarea');
        
        fields.forEach(field => {
            // Validate on blur
            field.addEventListener('blur', function() {
                if (this.value.trim()) {
                    validateField(this);
                }
            });
            
            // Clear validation on focus
            field.addEventListener('focus', function() {
                this.classList.remove('is-invalid', 'is-valid');
                hideFieldError(this);
            });
            
            // Real-time email validation
            if (field.type === 'email') {
                field.addEventListener('input', debounce(function() {
                    if (this.value.trim()) {
                        validateField(this);
                    }
                }, 500));
            }
        });
    }
    
    /**
     * Utility functions
     */
    function createMessagesDiv(form) {
        const messagesDiv = document.createElement('div');
        messagesDiv.className = 'form-messages mt-3';
        messagesDiv.id = 'formMessages';
        form.appendChild(messagesDiv);
        return messagesDiv;
    }
    
    function showFormMessage(container, message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        
        const icon = type === 'success' ? 'fas fa-check-circle' : 
                    type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-exclamation-circle';
        
        container.className = `form-messages alert ${alertClass} alert-dismissible fade show mt-3`;
        container.innerHTML = `
            <i class="${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Auto-dismiss success messages
        if (type === 'success') {
            setTimeout(() => {
                if (container.parentNode) {
                    container.style.opacity = '0';
                    setTimeout(() => {
                        if (container.parentNode) {
                            container.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
    }
    
    function setLoadingState(button, isLoading) {
        if (!button) return;
        
        if (isLoading) {
            button.disabled = true;
            button.dataset.originalText = button.textContent || button.value;
            
            if (button.tagName === 'BUTTON') {
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            } else {
                button.value = 'Sending...';
            }
        } else {
            button.disabled = false;
            
            if (button.tagName === 'BUTTON') {
                button.textContent = button.dataset.originalText;
            } else {
                button.value = button.dataset.originalText;
            }
        }
    }
    
    function showFieldError(field, message) {
        hideFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }
    
    function hideFieldError(field) {
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidPhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Initialize form enhancements
    initFormEnhancements();
    
    function initFormEnhancements() {
        // Add character counter for textarea fields
        const textareas = document.querySelectorAll('textarea[maxlength]');
        textareas.forEach(textarea => {
            const maxLength = parseInt(textarea.getAttribute('maxlength'));
            const counter = document.createElement('div');
            counter.className = 'character-counter text-muted small mt-1';
            counter.textContent = `0 / ${maxLength}`;
            
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                counter.textContent = `${currentLength} / ${maxLength}`;
                
                if (currentLength > maxLength * 0.9) {
                    counter.classList.add('text-warning');
                } else {
                    counter.classList.remove('text-warning');
                }
            });
        });
        
        // Add floating labels effect
        const formGroups = document.querySelectorAll('.form-floating');
        formGroups.forEach(group => {
            const input = group.querySelector('input, textarea');
            const label = group.querySelector('label');
            
            if (input && label) {
                input.addEventListener('focus', function() {
                    label.style.color = 'var(--primary-color)';
                });
                
                input.addEventListener('blur', function() {
                    label.style.color = '';
                });
            }
        });
    }
});

// Export for potential use in other scripts
window.ContactForm = {
    validate: function(formSelector) {
        const form = document.querySelector(formSelector);
        return form ? validateForm(form) : false;
    },
    
    submit: function(formSelector) {
        const form = document.querySelector(formSelector);
        if (form) {
            form.dispatchEvent(new Event('submit'));
        }
    }
};
