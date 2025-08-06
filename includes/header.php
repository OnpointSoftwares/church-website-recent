<!-- header.php: Reusable site navbar/header for CEFC -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Christ Ekklesia Fellowship Chapel'; ?></title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png" sizes="32x32">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../style.css?v=20250727" rel="stylesheet">
    
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="../assets/images/logo.png" alt="Christ Ekklesians Fellowship Chapel Logo" class="navbar-logo me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="/index#home">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#about" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">About</a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="/about">About Us</a></li>
                            <li><a class="dropdown-item" href="/constitution">Constitution</a></li>
                            <li><a class="dropdown-item" href="/church-calendar">Calendar</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">    <link href="../assets/css/sound-media-ministry.css?v=20250727" rel="stylesheet">
                        <a class="nav-link" href="index#services">Services</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#ministries" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ministries
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/ministries/childrens-ministry">Children's Ministry</a></li>
                           <!-- <li><a class="dropdown-item" href="/ministries/youth-ministry">Youth Ministry</a></li> -->
                            <li><a class="dropdown-item" href="/ministries/worship-team">Worship Team</a></li>
                            <!-- <li><a class="dropdown-item" href="/ministries/prayer-ministry">Prayer & Intercession Ministry</a></li> -->
                            <li><a class="dropdown-item" href="/ministries/sound-media-ministry">Sound & Media Ministry</a></li>
                            <li><a class="dropdown-item" href="/ministries/ushering-ministry">Ushering Ministry</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index#contact">Contact</a>
                    </li>
                    <li class="nav-item d-none d-lg-block ms-2">
                        <a class="btn btn-outline-light px-4" href="/volunteers.php">
                            <i class="fas fa-hands-helping me-1"></i> Volunteer now
                        </a>
                       
                    </li>
                    <li class="nav-item d-none d-lg-block ms-2"> <a class="btn btn-outline-light px-4" href="/giving.php">
                            <i class="fas fa-hands-helping me-1"></i> Give now
                        </a></li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="volunteers.php">
                            <i class="fas fa-hands-helping me-1"></i> Volunteer now
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div style="height: 80px;"></div> <!-- Spacer for fixed navbar -->
