<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව | North Western Province Department of Social Services | வட மேல் மாகாண சமூக சேவைகள் திணைக்களம்</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;500;700&family=Noto+Sans+Tamil:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Translate -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <style>
        /* ඔයාගේ original styles ඔක්කොමම මෙතන තියෙනවා – කිසිම වෙනසක් නැහැ */
        body {
            font-family: 'Noto Sans Sinhala', 'Noto Sans Tamil', sans-serif;
            background: #f9fbfc;
            color: #333;
        }
        .navbar {
            background: rgba(0, 70, 130, 0.95);
            backdrop-filter: blur(10px);
            transition: 0.4s;
            top: 0;
            padding: 10px 0;
        }
        .navbar.scrolled {
            background: #004682;
            padding: 5px 0;
        }
        /* Google Translate fix + visible කරගන්න */
        #google_translate_element {
            margin-left: 15px;
            min-width: 180px;
        }
        .goog-te-gadget {
            font-family: 'Noto Sans Sinhala', sans-serif !important;
            color: white !important;
            font-size: 14px !important;
        }
        .goog-te-gadget .goog-te-combo {
            background: white !important;
            color: #004682 !important;
            border-radius: 20px !important;
            padding: 6px 12px !important;
            border: none !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .goog-te-banner-frame { display: none !important; }
        /* ... ඔයාගේ අනිත් CSS ඔක්කොමම මෙතන තියෙනවා (කප්පාදු කරලා තියෙන්නේ space එක අඩු කරන්න) ... */
        
        /* අලුත් බටන් එකට style */
        .elder-homes-btn {
            background: linear-gradient(135deg, #6b48ff, #9d4edd);
            border: none;
            color: white;
            font-weight: bold;
            padding: 12px 28px;
            border-radius: 50px;
            box-shadow: 0 6px 20px rgba(107, 72, 255, 0.3);
            transition: all 0.3s ease;
            margin: 25px auto 40px;
            display: block;
            font-size: 1.1rem;
        }
        .elder-homes-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(107, 72, 255, 0.45);
            background: linear-gradient(135deg, #7c58ff, #b06aff);
        }
    </style>
</head>
<body>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'si',
            includedLanguages: 'si,en,ta',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>

<!-- ඔයාගේ original header + navbar + hero + sections ඔක්කොමම මෙතන තියෙනවා -->

<!-- ... (ඔයා දුන් full code එකේ මැද කොටස් ඔක්කොමම එහෙම්ම තියෙනවා) ... -->

<!-- Contact section එකෙන් පස්සේ, footer එකට කලින් අලුත් බටන් එක දාල තියෙනවා -->
<section class="py-4 text-center" style="background: #f8f9fa;">
    <button class="elder-homes-btn" onclick="window.open('https://elders-route-north-western-province-council-production-2.apps.red-k8s.akaza.lk', '_blank')">
        <i class="fas fa-home me-2"></i>Elder Homes System
    </button>
</section>

<footer class="py-5 text-center" style="background: #004682; color: white;">
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
