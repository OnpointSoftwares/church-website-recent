<!-- footer.php: Reusable site footer for CEFC -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h5>Christ Ekklesia Fellowship Chapel</h5>
                <p>Where Christ takes the preeminence of our worship. Join us in celebrating the supremacy of Jesus Christ in all things.</p>
            </div>
            <div class="col-lg-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="/index">Home</a></li>
                    <li><a href="/index#about">About</a></li>
                    <li><a href="/index#services">Services</a></li>
                    <li><a href="/index#ministries">Ministries</a></li>
                    <li><a href="/index#contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h5>Follow Us</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white fs-4"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-lg-12 text-center">
                <p>&copy; 2025 Christ Ekklesia Fellowship Chapel. All rights reserved.</p>
                <p class="mb-0">Crafted by 
                    <a href="https://onpointsoft.pythonanywhere.com" target="_blank" rel="noopener" class="text-white text-decoration-underline">
                        Onpoint Softwares Solutions
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            // Only smooth scroll if anchor is on this page
            const targetId = this.getAttribute('href');
            if (targetId.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            observer.observe(card);
        });

        // Contact form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
    </script>
  <script src="../assets/js/events.js"></script>
  <script src="../assets/js/sermons.js"></script>
</body>
</html>
