<?php
/**
 * SEO Configuration for Christ Ekklesia Fellowship Chapel
 * 
 * Centralized SEO settings and meta data for all pages
 * 
 * @version 1.0.0
 * @author Onpoint Softwares Solutions
 */

// Website base information
$siteConfig = [
    'siteName' => 'Christ Ekklesia Fellowship Chapel',
    'siteTagline' => 'Where Christ Takes the Preeminence of Our Worship',
    'siteUrl' => 'https://christekklesians.org', // Update with actual domain
    'siteLogo' => '/assets/images/logo.png',
    'defaultImage' => '/assets/images/church-hero.jpg', // Add a default social sharing image
    'language' => 'en',
    'locale' => 'en_US',
    'timezone' => 'Africa/Nairobi',
    'organization' => [
        'name' => 'Christ Ekklesia Fellowship Chapel',
        'type' => 'Church',
        'address' => [
            'street' => 'kabarak,Nakuru', // Update with actual address
            'city' => 'Nakuru',
            'region' => 'Nakuru County',
            'country' => 'Kenya',
            'postalCode' => '00100'
        ],
        'phone' => '+254-724740854', // Update with actual phone
        'email' => 'info@christekklesiafc.org', // Update with actual email
        'foundingDate' => '2019', // Update with actual founding date
    ]
];

// Page-specific SEO data
$seoPages = [
    'index' => [
        'title' => 'Christ Ekklesia Fellowship Chapel - Where Christ Takes Preeminence',
        'description' => 'Join Christ Ekklesia Fellowship Chapel in Nakuru, Kenya. Experience authentic worship, biblical teaching, and fellowship rooted in God\'s love. Sunday services, ministries, and community outreach.',
        'keywords' => 'Christ Ekklesia Fellowship Chapel, church Nakuru, Christian worship, biblical teaching, fellowship, Sunday service, Kenya church, Christian community, worship service',
        'type' => 'website',
        'image' => '/assets/images/church-hero.jpg'
    ],
    
    'about' => [
        'title' => 'About Us - Christ Ekklesia Fellowship Chapel',
        'description' => 'Learn about Christ Ekklesia Fellowship Chapel\'s mission, vision, and values. Discover our commitment to biblical truth, authentic worship, and community fellowship in Nairobi, Kenya.',
        'keywords' => 'about Christ Ekklesia, church mission, church vision, biblical truth, Christian values, Nairobi church history',
        'type' => 'article',
        'image' => '/assets/images/about-church.jpg'
    ],
    
    'constitution' => [
        'title' => 'Church Constitution - Christ Ekklesia Fellowship Chapel',
        'description' => 'Read the official constitution of Christ Ekklesia Fellowship Chapel. Learn about our governance, beliefs, membership requirements, and organizational structure.',
        'keywords' => 'church constitution, governance, beliefs, membership, organizational structure, church bylaws',
        'type' => 'article',
        'image' => '/assets/images/constitution.jpg'
    ],
    
    'church-calendar' => [
        'title' => 'Church Calendar & Events - Christ Ekklesia Fellowship Chapel',
        'description' => 'Stay updated with upcoming events, services, and activities at Christ Ekklesia Fellowship Chapel. View our church calendar for worship services, special events, and ministry activities.',
        'keywords' => 'church calendar, events, worship services, ministry activities, church schedule, upcoming events',
        'type' => 'website',
        'image' => '/assets/images/calendar-events.jpg'
    ],
    
    'volunteers' => [
        'title' => 'Volunteer Opportunities - Christ Ekklesia Fellowship Chapel',
        'description' => 'Join our volunteer team and serve in various ministries at Christ Ekklesia Fellowship Chapel. Discover opportunities in worship, children\'s ministry, ushering, and more.',
        'keywords' => 'volunteer opportunities, church ministries, serve, worship team, children ministry, ushering, sound ministry',
        'type' => 'website',
        'image' => '/assets/images/volunteers.jpg'
    ],
    
    'giving' => [
        'title' => 'Give & Support - Christ Ekklesia Fellowship Chapel',
        'description' => 'Support the ministry of Christ Ekklesia Fellowship Chapel through tithes, offerings, and donations. Learn about our giving options and how your support makes a difference.',
        'keywords' => 'church giving, tithes, offerings, donations, support ministry, online giving',
        'type' => 'website',
        'image' => '/assets/images/giving.jpg'
    ],
    
    // Ministry pages
    'ministries/childrens-ministry' => [
        'title' => 'Children\'s Ministry - Christ Ekklesia Fellowship Chapel',
        'description' => 'Nurturing young hearts for Christ through engaging Bible lessons, fun activities, and age-appropriate worship. Join our Children\'s Ministry at Christ Ekklesia Fellowship Chapel.',
        'keywords' => 'children ministry, kids church, Sunday school, Bible lessons, children worship, youth programs',
        'type' => 'article',
        'image' => '/assets/images/childrens-ministry.jpg'
    ],
    
    'ministries/worship-team' => [
        'title' => 'Worship Team - Christ Ekklesia Fellowship Chapel',
        'description' => 'Join our worship team and lead the congregation in spirit-filled worship. We welcome musicians, singers, and worship leaders to serve in our music ministry.',
        'keywords' => 'worship team, music ministry, church music, worship leaders, musicians, singers, praise and worship',
        'type' => 'article',
        'image' => '/assets/images/worship-team.jpg'
    ],
    
    'ministries/sound-media-ministry' => [
        'title' => 'Sound & Media Ministry - Christ Ekklesia Fellowship Chapel',
        'description' => 'Serve behind the scenes in our Sound & Media Ministry. Help with audio, visual, and technical aspects of our worship services and events.',
        'keywords' => 'sound ministry, media ministry, audio visual, technical ministry, church technology',
        'type' => 'article',
        'image' => '/assets/images/sound-media.jpg'
    ],
    
    'ministries/ushering-ministry' => [
        'title' => 'Ushering Ministry - Christ Ekklesia Fellowship Chapel',
        'description' => 'Welcome visitors and assist congregation members as part of our Ushering Ministry. Be the friendly face that greets people at Christ Ekklesia Fellowship Chapel.',
        'keywords' => 'ushering ministry, church ushers, hospitality, welcome team, church service',
        'type' => 'article',
        'image' => '/assets/images/ushering.jpg'
    ]
];

// Get current page SEO data
function getCurrentPageSEO() {
    global $seoPages, $siteConfig;
    
    $currentPath = $_SERVER['REQUEST_URI'];
    $currentPath = trim(parse_url($currentPath, PHP_URL_PATH), '/');
    
    // Default to index if empty
    if (empty($currentPath) || $currentPath === 'index') {
        $currentPath = 'index';
    }
    
    // Get SEO data for current page
    $seoData = $seoPages[$currentPath] ?? $seoPages['index'];
    
    // Add site defaults
    $seoData['siteName'] = $siteConfig['siteName'];
    $seoData['siteUrl'] = $siteConfig['siteUrl'];
    $seoData['locale'] = $siteConfig['locale'];
    $seoData['language'] = $siteConfig['language'];
    
    // Ensure image has full URL
    if (isset($seoData['image']) && !str_starts_with($seoData['image'], 'http')) {
        $seoData['image'] = $siteConfig['siteUrl'] . $seoData['image'];
    }
    
    return $seoData;
}

// Generate structured data for organization
function getOrganizationStructuredData() {
    global $siteConfig;
    
    $org = $siteConfig['organization'];
    
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Church',
        'name' => $org['name'],
        'url' => $siteConfig['siteUrl'],
        'logo' => $siteConfig['siteUrl'] . $siteConfig['siteLogo'],
        'description' => 'A Christian church dedicated to worship, fellowship, and community service in Nakuru, Kenya.',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $org['address']['street'],
            'addressLocality' => $org['address']['city'],
            'addressRegion' => $org['address']['region'],
            'addressCountry' => $org['address']['country'],
            'postalCode' => $org['address']['postalCode']
        ],
        'telephone' => $org['phone'],
        'email' => $org['email'],
        'foundingDate' => $org['foundingDate'],
        'sameAs' => [
            // Add social media URLs here
            // 'https://www.facebook.com/christekklesiafc',
            // 'https://www.instagram.com/christekklesiafc',
            // 'https://www.youtube.com/christekklesiafc'
        ]
    ];
}

// Generate breadcrumb structured data
function getBreadcrumbStructuredData($currentPath) {
    global $siteConfig;
    
    $breadcrumbs = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => []
    ];
    
    // Always start with home
    $breadcrumbs['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => $siteConfig['siteUrl']
    ];
    
    // Add current page if not home
    if ($currentPath !== 'index' && !empty($currentPath)) {
        $pathParts = explode('/', $currentPath);
        $position = 2;
        $currentUrl = $siteConfig['siteUrl'];
        
        foreach ($pathParts as $part) {
            $currentUrl .= '/' . $part;
            $name = ucwords(str_replace('-', ' ', $part));
            
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $name,
                'item' => $currentUrl
            ];
            
            $position++;
        }
    }
    
    return $breadcrumbs;
}

// Get robots meta content based on page
function getRobotsContent($currentPath) {
    // Pages that should not be indexed
    $noIndexPages = ['admin', 'volunteers', 'giving', 'error'];
    
    foreach ($noIndexPages as $noIndexPage) {
        if (str_contains($currentPath, $noIndexPage)) {
            return 'noindex, nofollow';
        }
    }
    
    return 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
}

// Generate canonical URL
function getCanonicalUrl($currentPath) {
    global $siteConfig;
    
    $cleanPath = trim($currentPath, '/');
    if (empty($cleanPath) || $cleanPath === 'index') {
        return $siteConfig['siteUrl'];
    }
    
    return $siteConfig['siteUrl'] . '/' . $cleanPath;
}
/**
 * Get site configuration
 * 
 * @return array Site configuration array
 */
function getSiteConfig() {
    global $siteConfig;
    return $siteConfig;
}

/**
 * Get SEO data for a specific page
 * 
 * @param string $page Page identifier
 * @return array SEO data for the page
 */
function getSeoData($page = 'index') {
    global $seoPages;
    return isset($seoPages[$page]) ? $seoPages[$page] : $seoPages['index'];
}

/**
 * Generate meta tags for a page
 * 
 * @param string $page Page identifier
 * @return string HTML meta tags
 */
function generateMetaTags($page = 'index') {
    global $siteConfig, $seoPages;
    
    $seo = getSeoData($page);
    $baseUrl = $siteConfig['siteUrl'];
    
    $html = "";
    $html .= "<title>" . htmlspecialchars($seo['title']) . "</title>\n";
    $html .= "<meta name='description' content='" . htmlspecialchars($seo['description']) . "'>\n";
    $html .= "<meta name='keywords' content='" . htmlspecialchars($seo['keywords']) . "'>\n";
    $html .= "<meta property='og:title' content='" . htmlspecialchars($seo['title']) . "'>\n";
    $html .= "<meta property='og:description' content='" . htmlspecialchars($seo['description']) . "'>\n";
    $html .= "<meta property='og:image' content='" . $baseUrl . $seo['image'] . "'>\n";
    $html .= "<meta property='og:type' content='" . $seo['type'] . "'>\n";
    
    return $html;
}

?>
