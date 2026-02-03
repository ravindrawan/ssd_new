<!DOCTYPE html>
<html lang="si">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව | North Western Province Department of Social Services | வட மேல் மாகாண
        சமூக சேவைகள் திணைக்களம்</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts for Sinhala & Tamil -->
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;500;700&family=Noto+Sans+Tamil:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Translate Script -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateInit"></script>
    <style>
        body {
            font-family: 'Noto Sans Sinhala', 'Noto Sans Tamil', sans-serif;
            background: #f9fbfc;
            color: #333;
        }

        /* Navbar එක පහත් කිරීම සඳහා වූ නව CSS */
        .navbar {
            background: rgba(0, 70, 130, 0.95);
            backdrop-filter: blur(10px);
            transition: 0.4s;
            top: 0;
            /* Changed from 30px to 0 for better mobile usage, or keep 30 if strictly required but handle mobile */
            padding: 10px 0;
        }

        }

        .navbar.scrolled {
            background: #004682;
            padding: 5px 0;
        }

        /* Google Translate - Custom Floating Look if desired, or Navbar integrated */
        #google_translate_element {
            margin-left: 20px;
        }

        .goog-te-gadget {
            font-family: 'Noto Sans Sinhala', sans-serif !important;
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 13px !important;
        }

        .goog-te-gadget .goog-te-combo {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 13px !important;
            outline: none;
            cursor: pointer;
        }

        .goog-te-gadget .goog-te-combo option {
            color: #333;
            background: white;
        }

        /* Fix for Anchor Links (Scroll Offset) */
        section {
            scroll-margin-top: 100px;
        }

        .hero {
            min-height: 100vh;
            /* Fallback */
            min-height: 100dvh;
            /* Mobile browser friendly */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
            padding-top: 120px;
            /* Space for navbar */
            padding-bottom: 50px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: 0.4s;
        }

        .glass-card:hover {
            transform: translateY(-10px);
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
        }

        EMPTY .parallax {
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
        }

        footer {
            background: #004682;
            color: white;
        }

        /* Google Translate Widget Styling - Navbar එක ඇතුළේ යොදා ගැනීමට */
        #google_translate_element {
            margin-left: 20px;
        }

        .goog-te-gadget {
            font-family: 'Noto Sans Sinhala', sans-serif !important;
            color: white !important;
            font-size: 14px !important;
        }

        .goog-te-gadget .goog-te-combo {
            background: white;
            color: #004682;
            border-radius: 20px;
            padding: 6px 14px;
            border: none;
            font-weight: bold;
            font-size: 14px !important;
            outline: none;
        }

        .goog-te-banner-frame {
            display: none !important;
        }

        .goog-te-menu-value span {
            color: #004682 !important;
        }

        /* Transparent Glassmorphism News Ticker inside Hero */
        .news-ticker-hero {
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 12px 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            overflow: hidden;
            z-index: 10;
            animation: fadeInDown 1.5s ease-out;
        }

        .ticker-label {
            background: linear-gradient(135deg, #004a8f, #0066cc);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .ticker-content {
            flex: 1;
            margin-left: 25px;
            overflow: hidden;
            white-space: nowrap;
        }

        .ticker-items {
            display: inline-block;
            color: white;
            font-size: 1.05rem;
            padding-left: 100%;
            animation: scroll-left 45s linear infinite;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate(-50%, -30px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        .news-ticker-hero:hover .ticker-items {
            animation-play-state: paused;
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(0, 70, 130, 0.98);
                padding: 20px;
                border-radius: 15px;
                margin-top: 10px;
            }

            #google_translate_element {
                margin-left: 0;
                margin-top: 15px;
                width: 100%;
                text-align: center;
            }

            .news-ticker-hero {
                top: 100px;
                flex-direction: column;
                text-align: center;
                padding: 15px;
                border-radius: 20px;
            }

            .ticker-label {
                width: 100%;
                margin-bottom: 10px;
            }

            .ticker-content {
                margin-left: 0;
                width: 100%;
            }

            .hero {
                padding-top: 180px;
                /* More space for ticker on mobile */
                align-items: flex-start;
                /* Align content to top so it's not hidden behind ticker */
            }

            .hero .container {
                margin-top: 100px;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }

            .navbar-brand img {
                width: 40px;
            }

            .display-3 {
                font-size: 2.5rem;
            }

            .lead {
                font-size: 1.1rem;
            }

            .glass-card {
                padding: 20px !important;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .news-ticker-hero {
                width: 95%;
                font-size: 0.9rem;
            }

            .ticker-label {
                font-size: 1rem;
                padding: 8px 20px;
            }
        }

        /* Premium Offcanvas Styles */
        .glass-offcanvas {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
        }

        .offcanvas-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.4);
        }

        .download-list-item {
            transition: 0.3s;
            border-radius: 12px;
            margin-bottom: 8px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            padding: 15px;
            text-decoration: none;
            color: #333;
        }

        .download-list-item:hover {
            background: white;
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            color: #004682;
        }

        .download-icon {
            width: 45px;
            height: 45px;
            background: #ffebee;
            color: #d32f2f;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 15px;
        }
    </style>
</head>

<body>

    <script type="text/javascript">
        function googleTranslateInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'si',
                includedLanguages: 'si,en,ta',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }

        window.addEventListener('scroll', () => {
            document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 100);
        });
    </script>

   <!-- Header Bar - ඔයාගේ screenshot එකට match වෙන ලෙස + සියල්ල center align කරලා තවත් ලස්සනට හදලා -->
<header class="py-3 text-white" style="background: #004a8f;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center flex-wrap gap-4">
            <!-- Logo left -->
            <div class="flex-shrink-0">
                <img src="https://gsms.ceytech.lk/dist/img/nwp-logo.png" alt="ලෝගෝ" width="80" class="rounded-circle bg-white p-2 shadow">
            </div>

            <!-- Department Name + Links - එක දිගට center එකේ -->
            <div class="text-center flex-grow-1">
                <h4 class="fw-bold mb-2 fs-5">වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව</h4>
                <div class="d-inline-block">
                    <a href="#home" class="text-white mx-3 fw-bold text-decoration-none hover-yellow">මුල් පිටුව</a>
                    <span class="text-white opacity-75 mx-2">|</span>
                    <a href="#vision" class="text-white mx-3 fw-bold text-decoration-none hover-yellow">දැක්ම සහ මෙහෙවර</a>
                    <span class="text-white opacity-75 mx-2">|</span>
                    <a href="#rti" class="text-white mx-3 fw-bold text-decoration-none hover-yellow">තොරතුරු අයිතිය</a>
                    <span class="text-white opacity-75 mx-2">|</span>
                    <a href="#downloads" class="text-white mx-3 fw-bold text-decoration-none hover-yellow">ආකෘතිපත්‍ර</a>
                    <span class="text-white opacity-75 mx-2">|</span>
                    <a href="#services" class="text-white mx-3 fw-bold text-decoration-none hover-yellow">අපගේ සේවා</a>
                    <span class="text-white opacity-75 mx-2">|</span>
                    <a href="#contact" class="text-white mx-3 fw-bold text-decoration-none hover-yellow">සම්බන්ධතා</a>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* Hover yellow effect + underline */
    .hover-yellow:hover {
        color: #ffd700 !important;
        position: relative;
        transition: all 0.3s ease;
    }
    .hover-yellow:hover::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        bottom: -4px;
        left: 0;
        background-color: #ffd700;
    }

    /* Mobile responsive - links stack වෙලා center එකට එනවා */
    @media (max-width: 991px) {
        header .d-flex {
            flex-direction: column;
            text-align: center;
        }
        header h4 {
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        header .mx-3 {
            margin: 8px 12px !important;
            display: block;
        }
        header .opacity-75 {
            display: none; /* Mobile වල | hide කරලා clean look එකක් */
        }
    }

    @media (max-width: 576px) {
        header .mx-3 {
            font-size: 0.95rem;
            margin: 6px 10px !important;
        }
        header img {
            width: 70px;
        }
    }
</style>
    <!-- Hero Section -->
    <section id="home" class="hero text-center">
        <!-- Transparent Blurred News Ticker inside Hero -->
        <div class="news-ticker-hero">
            <div class="ticker-label">
                <i class="fas fa-bullhorn me-"> නවතම පුවත් </i>
            </div>
            <div class="ticker-content">
                <div class="ticker-items">
                    • පළාත් සමාජ සේවා දෙපාර්තමේන්තුවේ නව වෙබ් අඩවිය දියුණු කර ඇත
                </div>
            </div>
        </div>

        <div class="container" data-aos="fade-up">
            <h1 class="display-3 fw-bold mb-4">අසරණ ජනතාව බලගැන්වීම</h1>
            <p class="lead fs-2 mb-5">තිරසර ජාතික සංවර්ධනයේ කොටස්කරුවන් බවට පත්කිරීම</p>
            <a href="#vision" class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow">වැඩිදුර තොරතුරු <i
                    class="fas fa-arrow-down ms-2"></i></a>
        </div>
    </section>

    <!-- ඉතිරි sections සියල්ලම එලෙසම තියෙනවා (කිසිම වෙනසක් නැහැ) -->

    <!-- Vision & Mission -->
    <section id="vision" class="py-5 parallax"
        style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&q=80');">
        <div class="container py-5">
            <h2 class="text-center text-white mb-5 section-title" data-aos="fade-down">දැක්ම සහ මෙහෙවර</h2>
            <div class="row g-5">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="glass-card p-5 text-white text-center h-100">
                        <i class="fas fa-eye fa-4x mb-4 text-warning"></i>
                        <h3 class="fw-bold fs-3">දැක්ම</h3>
                        <p class="fs-5">අසරණභාවයට පත් ජනකොටස් තිරසාර ජාතික සංවර්ධනයේ කොටස්කරුවන් බවට පත් කිරීම</p>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="glass-card p-5 text-white text-center h-100">
                        <i class="fas fa-hand-holding-heart fa-4x mb-4 text-warning"></i>
                        <h3 class="fw-bold fs-3">මෙහෙවර</h3>
                        <p class="fs-5">විවිධ හේතූන් නිසා දිළිඳු හා අසරණභාවයට පත් වයඹ පළාතේ පුද්ගලයින්ගේ අවාසිදායක
                            තත්වන් අවම කිරීම උදෙසා සාධාරණ අයුරින් සහන සේවා සැපයීම තුළින් ජාතික සංවර්ධනයට දායක වීම</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="rti" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 section-title text-primary" data-aos="fade-up">තොරතුරු දැනගැනීමේ අයිතිය (RTI)
            </h2>
            <div class="glass-card p-5 mx-auto" style="max-width: 900px;" data-aos="zoom-in">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h4 class="fw-bold text-primary">තොරතුරු නිලධාරී</h4>
                        <p><strong>නම:</strong> දීප්ති ප්‍රදීපා ද සිල්වා මිය</p>
                        <p><strong>තනතුර:</strong> පරිපාලන නිලධාරී</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h4 class="fw-bold text-primary">නම් කළ නිලධාරී (අභියාචනා)</h4>
                        <p><strong>නම:</strong> ජී.ජී. දිලානි ගුණසිංහ මිය</p>
                        <p><strong>තනතුර:</strong> සමාජ සේවා පළාත් අධ්‍යක්ෂ - වයඹ</p>
                    </div>
                </div>
                <p class="mt-4">තොරතුරු අයිතිය පිළිබඳ වැඩි විස්තර සඳහා <a href="https://www.rticommission.lk"
                        target="_blank">RTI කොමිසම</a> වෙබ් අඩවියට පිවිසෙන්න.</p>
            </div>
        </div>
    </section>


<!-- Downloads Section - Complete with ALL PDFs (preview + download working for every one) -->
<section id="downloads" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 section-title text-primary fw-bold" data-aos="fade-up">ආකෘතිපත්‍ර බාගත කිරීම්</h2>
        <p class="text-center lead mb-5 text-muted">පහත අකෘති පත්‍ර බලා බාගත කරගන්න.</p>

        <div class="row g-4">
            <!-- 1. නිදන්ගත වකුගඩු රෝග ආධාර (මෙඩිකල් රිපෝට්) -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-file-medical fa-4x text-danger mb-3"></i>
                        <h5 class="card-title fw-bold">නිදන්ගත වකුගඩු රෝග ආධාර</h5>
                        <p class="card-text small">Chronic Kidney Disease Financial Assistance</p>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#ckdModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/මෙඩිකල් රිපෝට්.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. සිසුමිණ නැවත සමීක්ෂණ -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-graduation-cap fa-4x text-success mb-3"></i>
                        <h5 class="card-title fw-bold">සිසුමිණ ශිෂ්‍යාධාර නැවත සමීක්ෂණ</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#sisuminaReviewModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/සිසුමිණ නැවත සමීක්ෂණ.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. අනියම් සහනාධාර -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-hands-helping fa-4x text-warning mb-3"></i>
                        <h5 class="card-title fw-bold">අනියම් සහනාධාර</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#emergencyModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/අනියම් සහනාධාර.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. පඩි සටහන -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-file-invoice-dollar fa-4x text-info mb-3"></i>
                        <h5 class="card-title fw-bold">පඩි සටහන</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#salaryModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/පඩි සටහන.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. නිවාසගත කිරීම -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-home fa-4x text-success mb-3"></i>
                        <h5 class="card-title fw-bold">වැඩිහිටි නිවාසගත කිරීම</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#housingModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/නිවාසගත කිරීම.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 6. වෛද්‍යාධාර -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-medkit fa-4x text-danger mb-3"></i>
                        <h5 class="card-title fw-bold">විශේෂ වෛද්‍යාධාර</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#medicalAidModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/වෛද්‍යාධාර.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 7. සිසුමිණ (පූර්ණ) -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-book-reader fa-4x text-primary mb-3"></i>
                        <h5 class="card-title fw-bold">සිසුමිණ ශිෂ්‍යාධාර</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#sisuminaModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/සිසුමිණ.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 8. වන අලි වගා හානි -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-leaf fa-4x text-success mb-3"></i>
                        <h5 class="card-title fw-bold">වන අලි වගා හානි ආධාර</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#elephantModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/වන අලි වගා හානි.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 9. මහජනාධාර -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-users fa-4x text-info mb-3"></i>
                        <h5 class="card-title fw-bold">මහජනාධාර</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#publicAidModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/මහජනාධාර.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>

            <!-- 10. පුනරුත්ථාපනාධාර -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg border-0">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-heartbeat fa-4x text-danger mb-3"></i>
                        <h5 class="card-title fw-bold">පුනරුත්ථාපන ආධාර</h5>
                        <button class="btn btn-outline-info rounded-pill mb-2 w-100" data-bs-toggle="modal" data-bs-target="#rehabModal">
                            <i class="fas fa-eye me-2"></i>පෙරදැක්ම බලන්න
                        </button>
                        <a href="forms/පුනරුත්ථාපනාධාර.pdf" class="btn btn-success rounded-pill w-100" download>
                            <i class="fas fa-download me-2"></i>බාගත කරන්න
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ALL Modals (ඔයා යොදා ඇති සියලුම PDFs වලට preview modal එකක්) -->
<!-- 1. CKD -->
<div class="modal fade" id="ckdModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">නිදන්ගත වකුගඩු රෝග ආධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/මෙඩිකල් රිපෝට්.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/මෙඩිකල් රිපෝට්.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. සිසුමිණ නැවත සමීක්ෂණ -->
<div class="modal fade" id="sisuminaReviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">සිසුමිණ නැවත සමීක්ෂණ - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/සිසුමිණ නැවත සමීක්ෂණ.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/සිසුමිණ නැවත සමීක්ෂණ.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>


<!-- ALL Modals (ඔයා යොදා ඇති සියලුම PDFs වලට preview modal එකක්) -->
<!-- 1. CKD -->
<div class="modal fade" id="ckdModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">නිදන්ගත වකුගඩු රෝග ආධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/මෙඩිකල් රිපෝට්.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/මෙඩිකල් රිපෝට්.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. සිසුමිණ නැවත සමීක්ෂණ -->
<div class="modal fade" id="sisuminaReviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">සිසුමිණ නැවත සමීක්ෂණ - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/සිසුමිණ නැවත සමීක්ෂණ.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/සිසුමිණ නැවත සමීක්ෂණ.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. අනියම් සහනාධාර -->
<div class="modal fade" id="emergencyModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">අනියම් සහනාධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/අනියම් සහනාධාර.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/අනියම් සහනාධාර.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. පඩි සටහන -->
<div class="modal fade" id="salaryModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">පඩි සටහන - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/පඩි සටහන.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/පඩි සටහන.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 5. නිවාසගත කිරීම -->
<div class="modal fade" id="housingModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">වැඩිහිටි නිවාසගත කිරීම - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/නිවාසගත කිරීම.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/නිවාසගත කිරීම.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. වෛද්‍යාධාර -->
<div class="modal fade" id="medicalAidModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">විශේෂ වෛද්‍යාධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/වෛද්‍යාධාර.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/වෛද්‍යාධාර.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 7. සිසුමිණ -->
<div class="modal fade" id="sisuminaModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">සිසුමිණ ශිෂ්‍යාධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/සිසුමිණ.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/සිසුමිණ.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 8. වන අලි වගා හානි -->
<div class="modal fade" id="elephantModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">වන අලි වගා හානි ආධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/වන අලි වගා හානි.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/වන අලි වගා හානි.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 9. මහජනාධාර -->
<div class="modal fade" id="publicAidModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">මහජනාධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/මහජනාධාර.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/මහජනාධාර.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- 10. පුනරුත්ථාපනාධාර -->
<div class="modal fade" id="rehabModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">පුනරුත්ථාපන ආධාර - පෙරදැක්ම</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe src="forms/පුනරුත්ථාපනාධාර.pdf" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="forms/පුනරුත්ථාපනාධාර.pdf" class="btn btn-success rounded-pill px-5" download>බාගත කරන්න</a>
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">වසන්න</button>
            </div>
        </div>
    </div>
</div>

<!-- අපගේ සේවාවන් Section - සියලුම items default වශයෙන් closed තත්වයේ (එකක්වත් open නැහැ) -->
<section id="services" class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold text-primary fs-3">අපගේ සේවාවන්</h2>

        <div class="accordion" id="servicesAccordion">
            <!-- 1. මහජනාධාර ලබාදීම -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingMahajana">
                    <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMahajana">
                        මහජනාධාර ලබාදීම
                    </button>
                </h2>
                <div id="collapseMahajana" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body bg-white">
                        අඩු ආදායම්ලාභී ප්‍රජාව සඳහා මහජනාධාර ලබාදීම
                    </div>
                </div>
            </div>

            <!-- 2. සිසුමිණ ශිෂ්‍යාධාර ලබාදීම -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingSisumina">
                    <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSisumina">
                        සිසුමිණ ශිෂ්‍යාධාර ලබාදීම
                    </button>
                </h2>
                <div id="collapseSisumina" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body bg-white">
                        වැන්දඹු, විසුරුණු, රෝගී ආබාධ සහිත හා අසරණ පවුල් වල දරුවන් සඳහා සිසුමිණ ශිෂ්‍යාධාර ලබාදීම (අධ්‍යාපන ආධාර)
                    </div>
                </div>
            </div>

            <!-- 3. පිළිසරණීය නිවාස ආධාර ලබාදීම -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingPilisarana">
                    <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePilisarana">
                        පිළිසරණීය නිවාස ආධාර ලබාදීම
                    </button>
                </h2>
                <div id="collapsePilisarana" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body bg-white">
                        අඩු ආදායම්ලාභී පවුල් සඳහා පිළිසරණීය නිවාස ආධාර ලබාදීම
                    </div>
                </div>
            </div>

            <!-- 4. ලාදුරු රෝගය සඳහා ආධාර ගෙවීම -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingLaduru">
                    <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLaduru">
                        ලාදුරු රෝගය සඳහා ආධාර ගෙවීම
                    </button>
                </h2>
                <div id="collapseLaduru" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body bg-white">
                        ලාදුරු රෝගය වැළදුනු අඩු ආදායම්ලාභීන් සඳහා වෛද්‍ය නිර්දේශය මත ආධාර ගෙවීම
                    </div>
                </div>
            </div>

            <!-- 5. විශේෂ වෛද්‍යාධාර ගෙවීම -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingVishesha">
                    <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVishesha">
                        විශේෂ වෛද්‍යාධාර ගෙවීම
                    </button>
                </h2>
                <div id="collapseVishesha" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body bg-white">
                        දීර්ඝ කාලීනව ප්‍රතිකාර ලබාගත යුතු බවට වෛද්‍ය කමිටුවක නිර්දේශය ලැබූ රෝගීන් සඳහා විශේෂ වෛද්‍යාධාර ගෙවීම
                    </div>
                </div>
            </div>

            <!-- 6. වැඩිහිටි නිවාස පවත්වාගෙන යාම -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingWedihiti">
                    <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWedihiti">
                        වැඩිහිටි නිවාස පවත්වාගෙන යාම
                    </button>
                </h2>
                <div id="collapseWedihiti" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body bg-white">
                        වැඩිහිටියන් සඳහා නියමිත ප්‍රමිතියෙන් යුතු වැඩිහිටි නිවාස පවත්වාගෙන යාම
                    </div>
                </div>
            </div>

            <!-- 7. ආබාධිත පුද්ගලයින් සඳහා සේවා -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingAbaditha">
                    <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAbaditha">
                        ආබාධිත පුද්ගලයින් සඳහා සේවා
                    </button>
                </h2>
                <div id="collapseAbaditha" class="accordion-collapse collapse" data-bs-parent="#servicesAccordion">
                    <div class="accordion-body bg-white">
                        ආබාධ සහිත පුද්ගලයින් සඳහා නියමිත ප්‍රමිතියෙන් යුතු නිවාස, ආයතන, නිපුණතා සංවර්ධන මධ්‍යස්ථාන පිහිටුවා පවත්වාගෙන යාම, ඔවුන් පුනරුත්ථාපනය හා සංවර්ධනය කිරීම
                    </div>
                </div>
            </div>


<!-- 8. Subasadana -->
                <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button"
                            data-bs-toggle="collapse" data-bs-target="#offCollapse8">
                            වැඩිහිටි සුබසාධන ව්‍යාපෘති
                        </button>
                    </h2>
                    <div id="offCollapse8" class="accordion-collapse collapse"
                        data-bs-parent="#servicesAccordionOffcanvas">
                        <div class="accordion-body bg-white small">
                            වයඹ පළාත තුළ වෙසෙන වැඩිහිටි ප්‍රජාව සංවිධානගත කර වැඩිහිටි සුබසාධනය උදෙසා ව්‍යාපෘති
                            ක්‍රියාත්මක කිරීම
                        </div>
                    </div>
                </div>
                <!-- 9. Nadaththu -->
                <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button"
                            data-bs-toggle="collapse" data-bs-target="#offCollapse9">
                            නඩත්තු ආධාර ලබාදීම
                        </button>
                    </h2>
                    <div id="offCollapse9" class="accordion-collapse collapse"
                        data-bs-parent="#servicesAccordionOffcanvas">
                        <div class="accordion-body bg-white small">
                            අධ්‍යක්ෂවරයාගේ අනුමැතියෙන් හෝ අධිකරණ නියෝගයක් මඟින් හෝ ලියාපදිංචි හෝ ස්වේච්ඡා පදනමකින්
                            ක්‍රියාත්මක වන නිවාසයක් වෙත ඇතුළත් කරනු ලබන වැඩිහිටියන් හෝ ආබාධිත පුද්ගලයින් වෙනුවෙන් නඩත්තු
                            ආධාර ලබාදීම
                        </div>
                    </div>
                </div>
                <!-- 10. Ayathana -->
                <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold fs-5 text-primary bg-light rounded" type="button"
                            data-bs-toggle="collapse" data-bs-target="#offCollapse10">
                            ආයතන අධීක්ෂණය
                        </button>
                    </h2>
                    <div id="offCollapse10" class="accordion-collapse collapse"
                        data-bs-parent="#servicesAccordionOffcanvas">
                        <div class="accordion-body bg-white small">
                            වැඩිහිටි පුද්ගලයින් හා අබාධිත පුද්ගලයින් නේවාසිකව තබා ගන්නා හෝ එම අය වෙනුවෙන් සේවා සපයන හෝ
                            වයඹ පළාත තුළ පිහිටි සියලුම ආයතන අධීක්ෂණය
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        </div>
    </div>
</section>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal"></button>
            </div>
        </div>
    </div>
</div>
<!-- Feedback -->
<section id="feedback" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 section-title text-primary" data-aos="fade-up">ඔබගේ අදහස් ප්‍රකාශ කරන්න</h2>
        <div class="glass-card p-5 mx-auto" style="max-width: 800px;" data-aos="fade-up">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = htmlspecialchars($_POST['name']);
                $email = htmlspecialchars($_POST['email']);
                $message = htmlspecialchars($_POST['message']);
                file_put_contents('feedback.txt', date('Y-m-d H:i:s') . " | $name | $email | $message\n", FILE_APPEND);
                echo '<div class="alert alert-success text-center">ස්තූතියි! ඔබගේ අදහස සාර්ථකව ලැබුණි.</div>';
            }
            ?>
            <form method="post">
                <div class="mb-3"><input type="text" name="name" class="form-control rounded-pill" placeholder="ඔබගේ නම" required></div>
                <div class="mb-3"><input type="email" name="email" class="form-control rounded-pill" placeholder="විද්‍යුත් තැපැල්" required></div>
                <div class="mb-3"><textarea name="message" class="form-control rounded-3" rows="6" placeholder="ඔබගේ අදහස් / යෝජනා" required></textarea></div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">එවන්න</button>
            </form>
        </div>
    </div>
</section>

<!-- Contact Section - ඔයාගේ code එකට හරියටම insert කරලා fix කළ version (clickable links + correct icons) -->
<section id="contact" class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-5 section-title text-primary" data-aos="fade-up">සම්බන්ධතා තොරතුරු</h2>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="glass-card p-5 h-100">
                    <div class="text-center mb-4">
                        <i class="fas fa-building fa-4x text-primary mb-3"></i>
                        <h4 class="fw-bold">වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව</h4>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item py-3"><i class="fas fa-map-marker-alt text-primary me-3"></i><strong>ලිපිනය:</strong> පළාත් සභා සංකීර්ණය, කුරුණෑගල</li>
                        <li class="list-group-item py-3"><i class="fas fa-phone text-primary me-3"></i><strong>දුරකථන:</strong> <a href="tel:0372223483" class="text-primary text-decoration-none hover-underline">037-2223483</a></li>
                        <li class="list-group-item py-3"><i class="fas fa-fax text-primary me-3"></i><strong>ෆැක්ස්:</strong> <a href="tel:0372224976" class="text-primary text-decoration-none hover-underline">037-2224976</a></li>
                        <li class="list-group-item py-3"><i class="fas fa-envelope text-primary me-3"></i><strong>ඊමේල්:</strong> <a href="mailto:socidepnwp@gmail.com" class="text-primary text-decoration-none hover-underline">socidepnwp@gmail.com</a></li>
                        <li class="list-group-item py-3"><i class="fas fa-globe text-primary me-3"></i><strong>වෙබ් අඩවිය:</strong> <a href="http://www.socialdept.nw.gov.lk" target="_blank" class="text-primary text-decoration-none hover-underline">www.socialdept.nw.gov.lk</a></li>
                        <li class="list-group-item py-3"><i class="fab fa-youtube text-primary me-3"></i><strong>Youtube:</strong> <a href="https://www.youtube.com/@socialchap" target="_blank" class="text-primary text-decoration-none hover-underline">http://www.youtube.com/@socialchap</a></li>
                        <li class="list-group-item py-3"><i class="fab fa-facebook text-primary me-3"></i><strong>Facebook page:</strong> <a href="https://www.facebook.com/socialchap" target="_blank" class="text-primary text-decoration-none hover-underline">socialchap</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.456!2d80.364!3d7.483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae338d0b3b3b3b3%3A0xprovincialcouncilkurunegala!2sNorth%20Western%20Provincial%20Council!5e0!3m2!1ssi!2slk!4v1730000000000" width="100%" height="450" style="border:0; border-radius:20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>

<style>
    .hover-underline:hover {
        text-decoration: underline !important;
    }
</style>

<footer class="py-5 text-center">
    <p>© 2026 වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව. සියලුම හිමිකම් ඇවිරිණි.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1200, once: true });
    window.addEventListener('scroll', () => {
        document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 100);
    });
</script>
</body>
</html>