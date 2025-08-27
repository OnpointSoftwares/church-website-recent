<?php 
/**
 * Church Calendar Page - Christ Ekklesia Fellowship Chapel
 * 
 * Production-ready calendar page with SEO optimization and enhanced content.
 */

// SEO Configuration
$pageTitle = "2025 Ministry Calendar - Christ Ekklesia Fellowship Chapel | Year of Eternal Legislation";
$pageDescription = "Discover our 2025 Ministry Calendar at Christ Ekklesia Fellowship Chapel. Join us for worship services, youth outings, evangelism crusades, and community outreach in Kabarak, Nakuru.";
$pageKeywords = "church calendar 2025, ministry events, worship services, youth ministry, evangelism, community outreach, Christ Ekklesia events, Kabarak church calendar";
$pageType = "article";
$pageImage = "/assets/images/calendar-hero.jpg";

// Page-specific scripts
$pageScripts = [
    '/assets/js/calendar-page.js'
];

include dirname(__DIR__) . '/includes/header.php'; 
?>

<!-- Main Content -->
<main id="main-content" class="calendar-section container-fluid px-0">
    <!-- Calendar Hero Section -->
    <section class="hero-section position-relative mb-5" style="background: linear-gradient(rgba(96, 55, 158, 0.8), rgba(142, 68, 173, 0.8)), url('/assets/images/calendar-hero.jpg') center/cover no-repeat; min-height: 400px; display: flex; align-items: center; justify-content: center;">
        <div class="container text-center text-white py-5">
            <div class="hero-content animate-fade-in">
                <h1 class="display-4 fw-bold mb-3" style="text-shadow: 0 4px 20px rgba(0,0,0,0.3);">2025 Ministry Calendar</h1>
                <p class="lead mb-4">Our Year of Eternal Legislation</p>
                <div class="hero-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center bg-transparent">
                            <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Calendar 2025</li>
                        </ol>
                    </nav>
                </div>
                <div class="calendar-year-badge">
                    <span class="badge bg-light text-primary fs-6 px-4 py-2">Year 2025</span>
                </div>
            </div>
        </div>
    </section>
    <div class="container" style="max-width: 900px; margin-bottom: 40px;">
        <h1 class="mb-4 text-center">2025 Ministry Calendar</h1>
        <div class="calendar-meta text-center">This calendar reflects the resolutions of the leadership meeting held on <b>January 25, 2025</b> at Lake Bogoria Spa, Kabarak.</div>
        <div class="calendar-section calendar-quarter">
            <div class="text-center mb-2">
                <img src="../assets/images/worship-team.jpg" alt="Quarter 1 Worship" class="rounded shadow" style="max-width: 320px; width:100%; height:auto; object-fit:cover;">
            </div>
            <h3><span class="icon">&#128736;</span> QUARTER 1: JANUARY - MARCH 2025</h3>
            <ul>
                <li><b>Primary Focus:</b> Church Construction Completion: Finalizing flooring (KSh 158,000), toilets (KSh 50,000), roofing (KSh 60,000), walling, and media center to ensure all-weather functionality.</li>
            </ul>
        </div>
        <div class="calendar-section calendar-quarter">
            <div class="text-center mb-2">
                <img src="../assets/images/children.jpg" alt="Quarter 2 Children" class="rounded shadow" style="max-width: 320px; width:100%; height:auto; object-fit:cover;">
            </div>
            <h3><span class="icon">&#10013;&#65039;</span> QUARTER 2: APRIL - JUNE 2025</h3>
            <ul>
                <li><b>Youth Service:</b> Men-led ministry event</li>
                <li><b>Sunday School Outing:</b> Children's fellowship (preceding Youth Outing)</li>
                <li><b>Youth Outing:</b> Dedicated youth retreat/fellowship</li>
                <li><b>Mercy Visits:</b> Community outreach to vulnerable groups</li>
            </ul>
        </div>
        <div class="calendar-section calendar-quarter">
            <div class="text-center mb-2">
                <img src="../assets/images/prayer.jpg" alt="Quarter 3 Prayer" class="rounded shadow" style="max-width: 320px; width:100%; height:auto; object-fit:cover;">
            </div>
            <h3><span class="icon">&#128591;</span> QUARTER 3: JULY - SEPTEMBER 2025</h3>
            <ul>
                <li><b>Evangelism Crusade:</b> Community soul-winning campaign</li>
                <li><b>Worship Service:</b> Special gatherings for corporate praise</li>
                <li><b>Discipleship Intensive:</b> Leadership and member training</li>
                <li><b>Ladies Service:</b> Women's ministry fellowship</li>
            </ul>
        </div>
        <div class="calendar-section calendar-quarter">
            <div class="text-center mb-2">
                <img src="../assets/images/outdoor.jpg" alt="Quarter 4 Outdoor" class="rounded shadow" style="max-width: 320px; width:100%; height:auto; object-fit:cover;">
            </div>
            <h3><span class="icon">&#127881;</span> QUARTER 4: OCTOBER - DECEMBER 2025</h3>
            <ul>
                <li><b>Thanksgiving Service:</b> Gratitude celebration for God's faithfulness</li>
                <li><b>Baptism Ceremony:</b> Public declaration of faith</li>
                <li><b>Kingdom Works Appreciation:</b> Honoring volunteers and servants</li>
            </ul>
        </div>
        <div class="admin-notes mb-5">
            <h4>Administrative Notes</h4>
            <ol>
                <li><b>Church Registration:</b> Formally completed on April 9, 2025 (Reg. No. 32402, Republic of Kenya).</li>
                <li><b>Leadership Fasting:</b> Quarterly spiritual discipline (dates TBA).</li>
                <li><b>Financial Priorities:</b>
                    <ul>
                        <li>Clearance of outstanding debts</li>
                        <li>Management of recurrent expenditures</li>
                    </ul>
                </li>
            </ol>
            <div class="calendar-meta">All dates subject to confirmation. Watch bulletins for updates.</div>
        </div>
        <div class="text-center mb-4">
            <span class="text-muted">For details, contact: <a href="mailto:info@christekklesians.org">info@christekklesians.org</a>.</span>
        </div>
    </div>
    
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
