<?php
/**
 * SEO-Optimized Footer for Christ Ekklesia Fellowship Chapel
 * 
 * Enhanced with structured data, accessibility features,
 * and performance optimizations.
 */

// Include SEO configuration for contact info (with error handling)
try {
    require_once __DIR__ . '/seo-config.php';
    $siteConfig = function_exists('getSiteConfig') ? getSiteConfig() : [];
} catch (Exception $e) {
    $siteConfig = [];
    error_log('Footer SEO config error: ' . $e->getMessage());
}
?>

<footer class="footer" role="contentinfo" itemscope itemtype="https://schema.org/Organization">
    <div class="container">
        <div class="row">
            <!-- Organization Info -->
            <div class="col-lg-4 mb-4">
                <h5 itemprop="name">Christ Ekklesia Fellowship Chapel</h5>
                <p itemprop="description">Where Christ takes the preeminence of our worship. Join us in celebrating the supremacy of Jesus Christ in all things.</p>
                
                <!-- Contact Information with Schema -->
                <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt me-2" aria-hidden="true"></i>
                        <span itemprop="streetAddress">Rafiki,Kabarak</span><br>
                        <span itemprop="addressLocality">Nakuru</span>, 
                        <span itemprop="addressRegion">Kabarak</span> 
                        <span itemprop="postalCode">20157</span>
                    </p>
                </div>
                
                <p class="mb-2">
                    <i class="fas fa-phone me-2" aria-hidden="true"></i>
                    <a href="tel:+254724740854" itemprop="telephone" class="text-white text-decoration-none">+254724740854</a>
                </p>
                
                <p class="mb-2">
                    <i class="fas fa-envelope me-2" aria-hidden="true"></i>
                    <a href="mailto:info@christekklesians.org" itemprop="email" class="text-white text-decoration-none">info@christekklesiafc.org</a>
                </p>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-4 mb-4">
                <h5>Quick Links</h5>
                <nav aria-label="Footer navigation">
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/" class="text-white text-decoration-none hover-underline">Home</a></li>
                        <li class="mb-2"><a href="/#about" class="text-white text-decoration-none hover-underline">About Us</a></li>
                        <li class="mb-2"><a href="/#services" class="text-white text-decoration-none hover-underline">Services</a></li>
                        <li class="mb-2"><a href="/#ministries" class="text-white text-decoration-none hover-underline">Ministries</a></li>
                        <li class="mb-2"><a href="/volunteers" class="text-white text-decoration-none hover-underline">Volunteer</a></li>
                        <li class="mb-2"><a href="/calendar" class="text-white text-decoration-none hover-underline">Calendar</a></li>
                        <li class="mb-2"><a href="/giving" class="text-white text-decoration-none hover-underline">Giving</a></li>
                        <li class="mb-2"><a href="/#contact" class="text-white text-decoration-none hover-underline">Contact</a></li>
                    </ul>
                </nav>
            </div>
            
            <!-- Social Media & Service Times -->
            <div class="col-lg-4 mb-4">
                <h5>Connect With Us</h5>
                
                <!-- Service Times -->
                <div class="mb-3">
                    <h6 class="text-white-50">Service Times</h6>
                    <p class="mb-1"><strong>First Sunday Worship:</strong> 8:00 AM-9:30 AM</p>
                    <p class="mb-1"><strong>Second Sunday Worship:</strong> 10:30 AM-12:30 PM</p>
                    <p class="mb-1"><strong>Bible Study:</strong> Saturday 2:00 PM-4:00 PM</p>
                    <p class="mb-3"><strong>Prayer Meeting:</strong> Wednesday 5:30 PM-6:30 PM</p>
                </div>
                
                <!-- Social Media Links -->
                <div class="social-links">
                    <h6 class="text-white-50 mb-3">Follow Us</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-4 social-link" aria-label="Follow us on Facebook" rel="noopener">
                            <i class="fab fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="text-white fs-4 social-link" aria-label="Follow us on Twitter" rel="noopener">
                            <i class="fab fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="text-white fs-4 social-link" aria-label="Follow us on Instagram" rel="noopener">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="text-white fs-4 social-link" aria-label="Subscribe to our YouTube channel" rel="noopener">
                            <i class="fab fa-youtube" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="my-4 border-white-50">
        
        <!-- Copyright and Legal -->
        <div class="row">
            <div class="col-lg-8">
                <p class="mb-2">&copy; <?= date('Y') ?> <span itemprop="name">Christ Ekklesia Fellowship Chapel</span>. All rights reserved.</p>
                <p class="mb-0 small text-white-50">
                    <a href="/privacy-policy" class="text-white-50 text-decoration-none hover-underline me-3">Privacy Policy</a>
                    <a href="/terms-of-service" class="text-white-50 text-decoration-none hover-underline me-3">Terms of Service</a>
                    <a href="/sitemap.xml" class="text-white-50 text-decoration-none hover-underline">Sitemap</a>
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <p class="mb-0 small">Crafted with ❤️ by 
                    <a href="https://onpointsoft.pythonanywhere.com" target="_blank" rel="noopener" class="text-white text-decoration-none hover-underline">
                        Onpoint Softwares Solutions
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Performance-Optimized JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous" defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" defer></script>

<script>
/**
 * Core Site Functionality
 * Optimized for performance and accessibility
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling for anchor links
    function initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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
    
    // Intersection Observer for animations
    function initScrollAnimations() {
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                        observer.unobserve(entry.target); // Stop observing once animated
                    }
                });
            }, observerOptions);
            
            // Observe elements for animation
            document.querySelectorAll('.card, .feature-item, .ministry-card, .testimonial').forEach(element => {
                observer.observe(element);
            });
        }
    }
    
    // Enhanced form handling
    function initFormHandling() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            // Skip volunteer forms (they have their own handling)
            if (form.closest('.volunteer-portal')) return;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic form validation
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (isValid) {
                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success alert-dismissible fade show';
                    successAlert.innerHTML = `
                        <strong>Thank you!</strong> Your message has been received. We'll get back to you soon.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    
                    form.insertBefore(successAlert, form.firstChild);
                    form.reset();
                    
                    // Auto-dismiss after 5 seconds
                    setTimeout(() => {
                        successAlert.remove();
                    }, 5000);
                }
            });
        });
    }
    
    // Social media link tracking (for analytics)
    function initSocialTracking() {
        document.querySelectorAll('.social-link').forEach(link => {
            link.addEventListener('click', function() {
                // Google Analytics event tracking (if GA is loaded)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'social_click', {
                        'social_network': this.getAttribute('aria-label'),
                        'page_location': window.location.href
                    });
                }
            });
        });
    }
    
    // Navbar scroll effect
    function initNavbarEffects() {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            let lastScrollTop = 0;
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Add/remove background on scroll
                if (scrollTop > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                
                // Hide/show navbar on scroll direction
                if (scrollTop > lastScrollTop && scrollTop > 200) {
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    navbar.style.transform = 'translateY(0)';
                }
                
                lastScrollTop = scrollTop;
            }, { passive: true });
        }
    }
    
    // Initialize all functionality
    initSmoothScrolling();
    initScrollAnimations();
    initFormHandling();
    initSocialTracking();
    initNavbarEffects();
    
    // Performance monitoring
    if ('performance' in window) {
        window.addEventListener('load', function() {
            setTimeout(function() {
                const perfData = performance.getEntriesByType('navigation')[0];
                if (perfData && perfData.loadEventEnd > 0) {
                    console.log('Page load time:', perfData.loadEventEnd - perfData.fetchStart, 'ms');
                }
            }, 0);
        });
    }
});

// Service Worker registration for PWA capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful');
            })
            .catch(function(err) {
                console.log('ServiceWorker registration failed');
            });
    });
}
</script>

<!-- Load page-specific scripts -->
<?php if (isset($pageScripts) && is_array($pageScripts)): ?>
    <?php foreach ($pageScripts as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>" defer></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Google Analytics (conditional loading) -->
<?php if (defined('GA_MEASUREMENT_ID') && GA_MEASUREMENT_ID !== 'GA_MEASUREMENT_ID'): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= GA_MEASUREMENT_ID ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= GA_MEASUREMENT_ID ?>', {
        'anonymize_ip': true,
        'cookie_flags': 'SameSite=Strict;Secure'
    });
</script>
<?php else: ?>
<!-- Google Analytics not configured -->
<script>
    // Placeholder gtag function to prevent errors
    window.gtag = window.gtag || function() {
        console.log('Google Analytics not configured:', arguments);
    };
</script>
<?php endif; ?>
</body>
</html>
