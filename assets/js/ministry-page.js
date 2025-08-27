/**
 * Ministry Page JavaScript - Christ Ekklesia Fellowship Chapel
 * 
 * Enhanced functionality for ministry pages including animations,
 * interactive elements, and user engagement features.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize ministry page functionality
    initMinistryAnimations();
    initContactForms();
    initImageGallery();
    initScrollToTop();
    initSmoothScrolling();
    
    /**
     * Initialize scroll-triggered animations for ministry content
     */
    function initMinistryAnimations() {
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                        
                        // Add staggered animation for stat items
                        if (entry.target.classList.contains('ministry-stats')) {
                            const statItems = entry.target.querySelectorAll('.stat-item');
                            statItems.forEach((item, index) => {
                                setTimeout(() => {
                                    item.style.opacity = '1';
                                    item.style.transform = 'translateY(0)';
                                }, index * 200);
                            });
                        }
                        
                        // Animate guideline cards
                        if (entry.target.classList.contains('guidelines-grid')) {
                            const cards = entry.target.querySelectorAll('.guideline-card');
                            cards.forEach((card, index) => {
                                setTimeout(() => {
                                    card.style.opacity = '1';
                                    card.style.transform = 'translateY(0)';
                                }, index * 150);
                            });
                        }
                        
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            // Observe ministry sections
            document.querySelectorAll('.ministry-section, .ministry-overview, .ministry-stats, .guidelines-grid').forEach(section => {
                observer.observe(section);
            });
        }
    }
    
    /**
     * Initialize contact forms with validation
     */
    function initContactForms() {
        const forms = document.querySelectorAll('.ministry-contact-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        showFieldError(field, 'This field is required');
                    } else {
                        field.classList.remove('is-invalid');
                        hideFieldError(field);
                    }
                });
                
                // Email validation
                const emailFields = form.querySelectorAll('input[type="email"]');
                emailFields.forEach(field => {
                    if (field.value && !isValidEmail(field.value)) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        showFieldError(field, 'Please enter a valid email address');
                    }
                });
                
                if (isValid) {
                    submitMinistryForm(form);
                }
            });
        });
    }
    
    /**
     * Submit ministry form
     */
    function submitMinistryForm(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        
        // Simulate form submission (replace with actual endpoint)
        setTimeout(() => {
            // Show success message
            showSuccessMessage(form, 'Thank you for your interest! We\'ll get back to you soon.');
            form.reset();
            
            // Reset button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }, 2000);
    }
    
    /**
     * Initialize image gallery functionality
     */
    function initImageGallery() {
        const galleryImages = document.querySelectorAll('.ministry-gallery img');
        
        galleryImages.forEach(img => {
            img.addEventListener('click', function() {
                openImageModal(this.src, this.alt);
            });
            
            // Add hover effect
            img.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            img.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }
    
    /**
     * Initialize scroll to top button
     */
    function initScrollToTop() {
        // Create scroll to top button
        const scrollBtn = document.createElement('button');
        scrollBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
        scrollBtn.className = 'scroll-to-top btn btn-primary';
        scrollBtn.setAttribute('aria-label', 'Scroll to top');
        scrollBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        document.body.appendChild(scrollBtn);
        
        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        
        // Scroll to top functionality
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    /**
     * Initialize smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId && targetId !== '#') {
                    const target = document.querySelector(targetId);
                    if (target) {
                        e.preventDefault();
                        const navHeight = document.querySelector('.navbar')?.offsetHeight || 0;
                        const targetPosition = target.offsetTop - navHeight - 20;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    }
    
    /**
     * Utility functions
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showFieldError(field, message) {
        let errorDiv = field.parentNode.querySelector('.field-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'field-error text-danger small mt-1';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
    
    function hideFieldError(field) {
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    function showSuccessMessage(form, message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
        successDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        form.insertBefore(successDiv, form.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (successDiv.parentNode) {
                successDiv.remove();
            }
        }, 5000);
    }
    
    function openImageModal(src, alt) {
        // Create modal for image viewing
        const modal = document.createElement('div');
        modal.className = 'image-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            cursor: pointer;
        `;
        
        const img = document.createElement('img');
        img.src = src;
        img.alt = alt;
        img.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        `;
        
        modal.appendChild(img);
        document.body.appendChild(modal);
        
        // Close modal on click
        modal.addEventListener('click', function() {
            document.body.removeChild(modal);
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.parentNode) {
                document.body.removeChild(modal);
            }
        });
    }
    
    // Initialize CSS animations
    const style = document.createElement('style');
    style.textContent = `
        .animate-fade-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .ministry-section,
        .ministry-overview,
        .stat-item,
        .guideline-card {
            opacity: 0;
            transform: translateY(30px);
        }
        
        .stat-item {
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .guideline-card {
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .ministry-gallery img {
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .scroll-to-top {
            transition: opacity 0.3s ease;
        }
        
        .field-error {
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
});

// Export for potential use in other scripts
window.MinistryPage = {
    init: function() {
        // Re-initialize if needed
        document.dispatchEvent(new Event('DOMContentLoaded'));
    }
};
