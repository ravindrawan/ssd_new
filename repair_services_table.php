<?php
/**
 * Database Seeder and Structural Repair Script for Services Table
 * Place this file on your web server and access it via browser to execute:
 * e.g., http://your-domain/ssdweb/repair_services_table.php
 */

header('Content-Type: text/html; charset=utf-8');
require_once 'db_config.php';

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Repair & Seeder - Wayamba Social Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Outfit", sans-serif; background: #f1f5f9; padding: 40px 20px; color: #1e293b; line-height: 1.5; }
        .card { max-width: 750px; margin: 0 auto; background: white; border-radius: 16px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h1 { color: #991b1b; font-size: 1.8rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        h2 { font-size: 1.2rem; color: #1e3a5f; margin-top: 25px; margin-bottom: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; }
        .log-entry { font-family: monospace; font-size: 0.9rem; padding: 8px 12px; border-radius: 6px; margin-bottom: 6px; }
        .success { background: #dcfce7; color: #15803d; border-left: 4px solid #22c55e; }
        .info { background: #e0f2fe; color: #0369a1; border-left: 4px solid #0ea5e9; }
        .warning { background: #fef3c7; color: #b45309; border-left: 4px solid #f59e0b; }
        .error { background: #fee2e2; color: #b91c1c; border-left: 4px solid #ef4444; }
        .btn { display: inline-block; background: #991b1b; color: white; padding: 10px 20px; border-radius: 30px; text-decoration: none; font-weight: 500; margin-top: 20px; transition: 0.2s; }
        .btn:hover { background: #be123c; }
    </style>
</head>
<body>
<div class="card">
    <h1><span style="font-size: 2rem;">🛠️</span> Database Seeder & Repair Wizard</h1>
    <p>Running diagnostics and seeding tables for the <strong>Wayamba Provincial Social Services Department Web Portal</strong>...</p>';

if (isset($db_connection_error) && $db_connection_error !== null) {
    echo '<div class="log-entry error">❌ <strong>Database Connection Failed:</strong> ' . htmlspecialchars($db_connection_error) . '</div>';
    echo '<p>Please ensure XAMPP MySQL is active or check database credentials in <code>db_config.php</code>.</p>';
    echo '</div></body></html>';
    exit;
}

echo '<div class="log-entry success">✔ <strong>Connected successfully</strong> to database: <code>' . htmlspecialchars($db_name) . '</code></div>';

// 1. Create or repair `services` table structure
$table_exists = $conn->query("SHOW TABLES LIKE 'services'")->num_rows > 0;

if (!$table_exists) {
    echo '<div class="log-entry info">ℹ Table <code>services</code> not found. Creating it...</div>';
    $create_sql = "CREATE TABLE `services` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title_en` varchar(255) NOT NULL,
      `title_si` varchar(255) NOT NULL,
      `title_ta` varchar(255) NOT NULL,
      `short_desc_en` varchar(255) NOT NULL,
      `short_desc_si` varchar(255) NOT NULL,
      `short_desc_ta` varchar(255) NOT NULL,
      `icon_class` varchar(50) DEFAULT 'fa-concierge-bell',
      `icon_bg` varchar(255) DEFAULT 'linear-gradient(135deg, #1e3a5f, #2563eb)',
      `bullets_en` text DEFAULT NULL,
      `bullets_si` text DEFAULT NULL,
      `bullets_ta` text DEFAULT NULL,
      `long_desc_en` text DEFAULT NULL,
      `long_desc_si` text DEFAULT NULL,
      `long_desc_ta` text DEFAULT NULL,
      `sort_order` int(11) DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    if ($conn->query($create_sql) === TRUE) {
        echo '<div class="log-entry success">✔ Table <code>services</code> created successfully.</div>';
    } else {
        echo '<div class="log-entry error">❌ Error creating table: ' . htmlspecialchars($conn->error) . '</div>';
        exit;
    }
} else {
    echo '<div class="log-entry success">✔ Table <code>services</code> exists in database.</div>';
    
    // Check if the id column has auto_increment
    $desc_res = $conn->query("DESCRIBE services");
    $id_auto_increment = false;
    if ($desc_res) {
        while ($row = $desc_res->fetch_assoc()) {
            if ($row['Field'] === 'id' && strpos($row['Extra'], 'auto_increment') !== false) {
                $id_auto_increment = true;
            }
        }
    }

    if (!$id_auto_increment) {
        echo '<div class="log-entry warning">⚠ Column <code>id</code> is missing AUTO_INCREMENT constraint. Repairing column...</div>';
        // Alter table to add AUTO_INCREMENT
        if ($conn->query("ALTER TABLE services MODIFY id INT AUTO_INCREMENT") === TRUE) {
            echo '<div class="log-entry success">✔ Column <code>id</code> successfully modified to AUTO_INCREMENT.</div>';
        } else {
            echo '<div class="log-entry error">❌ Error modifying column: ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

// 2. Seeding Default services data
echo '<h2>⚙️ Seeding/Updating Default Services Data</h2>';

$default_services = [
    [
        'id' => 1,
        'title_en' => 'Public Assistance',
        'title_si' => 'මහජනාධාර ලබාදීම',
        'title_ta' => 'பொது உதவி',
        'short_desc_en' => 'Provision of public assistance for low-income communities',
        'short_desc_si' => 'අඩු ආදායම්ලාභී ප්‍රජාව සඳහා මහජනාධාර ලබාදීම',
        'short_desc_ta' => 'குறைந்த வருமானம் பெறும் சமூகங்களுக்கான பொது உதவி வழங்குதல்',
        'icon_class' => 'fa-hand-holding-usd',
        'icon_bg' => 'linear-gradient(135deg, #1e3a5f, #2563eb)',
        'bullets_en' => "Monthly financial aid payment from Rs. 250 to Rs. 500\nDisaster and emergency financial relief schemes\nSelf-employment and livelihood development support",
        'bullets_si' => "රු. 250 සිට රු. 500 දක්වා මාසික මූල්‍ය ආධාර ගෙවීම\nහදිසි ආපදා සහන මූල්‍ය ආධාර\nස්වයං රැකියා සහ ජීවනෝපාය සංවර්ධන ආධාර",
        'bullets_ta' => "ரூ. 250 முதல் ரூ. 500 வரையிலான மாதாந்திர நிதி உதவி\nபேரிடர் மற்றும் அவசர நிதி நிவாரணத் திட்டங்கள்\nசுயதொழில் மற்றும் வாழ்வாதார மேம்பாட்டு ஆதரவு",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 1
    ],
    [
        'id' => 2,
        'title_en' => 'Sisumina Scholarship',
        'title_si' => 'සිසුමිණ ශිෂ්‍යධාර ලබාදිම',
        'title_ta' => 'சிசுமின கல்வி உதவித்தொகை',
        'short_desc_en' => 'Educational assistance for children from vulnerable, widowed, or disabled families',
        'short_desc_si' => 'වැන්දඹු, විසුරුණු, රෝගී ආබාධ සහිත හා අසරණ පවුල් වල දරුවන් සඳහා සිසුමිණ ශිෂ්‍යධාර ලබාදීම (අධ්‍යාපන ආධාර)',
        'short_desc_ta' => 'கணவனை இழந்த, நோய்வாய்ப்பட்ட மற்றும் ஏழை குடும்பங்களைச் சேர்ந்த குழந்தைகளின் கல்விக்கான உதவி',
        'icon_class' => 'fa-graduation-cap',
        'icon_bg' => 'linear-gradient(135deg, #6d28d9, #8b5cf6)',
        'bullets_en' => "Monthly scholarship grants for school education\nProvision of school equipment, uniforms and books\nContinuous tracking and assistance for higher studies",
        'bullets_si' => "පාසල් දරුවන්ගේ අධ්‍යාපන කටයුතු සඳහා මාසික ශිෂ්‍යත්වාධාර\nපාසල් උපකරණ සහ පොත්පත් ලබාදීම\nඋසස් අධ්‍යාපන කටයුතු සඳහා අඛණ්ඩ අනුග්‍රහය සහ අධීක්ෂණය",
        'bullets_ta' => "பள்ளி கல்விக்கான மாதாந்திர உதவித்தொகை மானியங்கள்\nபள்ளி உபகரணங்கள், சீருடைகள் மற்றும் புத்தகங்கள் வழங்குதல்\nஉயர் படிப்புகளுக்கான தொடர்ச்சியான கண்காணிப்பு மற்றும் உதவி",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 2
    ],
    [
        'id' => 3,
        'title_en' => 'Pilisaraneeya Housing Assistance',
        'title_si' => 'පිළිසරණීය නිවාස ආධාර ලබාදීම',
        'title_ta' => 'பிலிசரணிய வீட்டு வசதி உதவி',
        'short_desc_en' => 'Providing housing construction and repair grants to low-income families',
        'short_desc_si' => 'අඩු ආදායම්ලාභී පවුල් සඳහා පිළිසරණීය නිවාස ආධාර ලබාදීම',
        'short_desc_ta' => 'குறைந்த வருமானம் கொண்ட குடும்பங்களுக்கு வீட்டு வசதி உதவிகளை வழங்குதல்',
        'icon_class' => 'fa-home',
        'icon_bg' => 'linear-gradient(135deg, #065f46, #10b981)',
        'bullets_en' => "Financial grants for constructing new houses\nMaterial assistance for repairing partially built houses\nSanitary and water facility improvement schemes",
        'bullets_si' => "නව නිවාස ඉදිකිරීම් සඳහා මූල්‍ය ආධාර ලබාදීම\nඅර්ධ නිවාස අලුත්වැඩියාවන් සඳහා ද්‍රව්‍යමය ආධාර\nසනීපාරක්ෂක සහ ජල පහසුකම් වැඩිදියුණු කිරීමේ සහන",
        'bullets_ta' => "புதிய வீடுகளை கட்டுவதற்கான நிதி மானியங்கள்\nபகுதி கட்டப்பட்ட வீடுகளை பழுதுபார்ப்பதற்கான பொருள் உதவி\nசுகாதார மற்றும் குடிநீர் வசதி மேம்பாட்டு திட்டங்கள்",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 3
    ],
    [
        'id' => 4,
        'title_en' => 'Leprosy Patient Assistance',
        'title_si' => 'ලාදුරු රෝගය සඳහා ආධාර ගෙවීම',
        'title_ta' => 'தொழுநோய் நோயாளிக்கான உதவி',
        'short_desc_en' => 'Monthly financial medical assistance for registered leprosy patients',
        'short_desc_si' => 'ලාදුරු රෝගය වැළදුනු අඩු ආදායම්ලාභීන් සඳහා වෛද්‍ය නිර්දේශය මත ආධාර ගෙවීම',
        'short_desc_ta' => 'தொழுநோயால் பாதிக்கப்பட்ட குறைந்த வருமானம் உடையவர்களுக்கு மாதாந்திர உதவி',
        'icon_class' => 'fa-medkit',
        'icon_bg' => 'linear-gradient(135deg, #9f1239, #e11d48)',
        'bullets_en' => "Monthly allowance based on medical recommendations\nSupport for medical followups and clinical treatments\nRehabilitation and social integration for patients and families",
        'bullets_si' => "වෛද්‍ය නිර්දේශ මත මාසික දීමනාවක් ගෙවීම\nසායනික ප්‍රතිකාර සහ වෛද්‍ය පරීක්ෂණ සඳහා සහාය\nරෝගීන් සමාජගත කිරීම සහ පවුල් පුනරුත්ථාපනය",
        'bullets_ta' => "மருத்துவ பரிந்துரைகளின் அடிப்படையில் மாதாந்திர கொடுப்பனவு\nமருத்துவ பின்தொடர்தல் மற்றும் மருத்துவ சிகிச்சைகளுக்கான ஆதரவு\nநோயாளிகள் மற்றும் குடும்பங்களுக்கான மறுவாழ்வு மற்றும் சமூக ஒருங்கிணைப்பு",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 4
    ],
    [
        'id' => 5,
        'title_en' => 'Special Medical Assistance',
        'title_si' => 'විශේෂ වෛද්‍යාධාර ගෙවීම',
        'title_ta' => 'சிறப்பு மருத்துவ உதவி',
        'short_desc_en' => 'Assistance for patients requiring long-term medical treatments and drugs',
        'short_desc_si' => 'දීර්ඝ කාලීනව ප්‍රතිකාර ලබාගත යුතු බවට වෛද්‍ය කමිටුවක නිර්දේශය ලැබූ රෝගීන් සඳහා විශේෂ වෛද්‍යාධාර ගෙවීම',
        'short_desc_ta' => 'நீண்ட கால சிகிச்சை தேவைப்படும் நோயாளிகளுக்கு சிறப்பு மருத்துவ உதவி',
        'icon_class' => 'fa-stethoscope',
        'icon_bg' => 'linear-gradient(135deg, #d97706, #f59e0b)',
        'bullets_en' => "Grants for patients suffering from kidney, cancer, or heart diseases\nFinancial support for purchasing prescribed drugs and tests\nRelief recommendations based on medical board reviews",
        'bullets_si' => "වකුගඩු, පිළිකා, හෘද රෝග වැනි දීර්ඝ කාලීන රෝගීන් සඳහා මාසික ආධාර\nවිශේෂ වෛද්‍ය පරීක්ෂණ සහ ඖෂධ මිලදී ගැනීම් සඳහා සහාය\nවෛද්‍ය කමිටු නිර්දේශ මත ලබාදෙන විශේෂ සහන",
        'bullets_ta' => "சிறுநீரகம், புற்றுநோய் அல்லது இதய நோய்களால் பாதிக்கப்பட்ட நோயாளிகளுக்கான மானியங்கள்\nபரிந்துரைக்கப்பட்ட மருந்துகள் மற்றும் சோதனைகளை வாங்குவதற்கான நிதி உதவி\nமருத்துவக் குழு பரிசீலனைகளின் அடிப்படையில் நிவாரணப் பரிந்துரைகள்",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 5
    ],
    [
        'id' => 6,
        'title_en' => 'Elders Homes Management',
        'title_si' => 'වැඩිහිටි නිවාස පවත්වාගනෙ යාම',
        'title_ta' => 'முதியோர் இல்லங்கள் மேலாண்மை',
        'short_desc_en' => 'Monitoring and maintaining standards of registered elder care homes',
        'short_desc_si' => 'වැඩිහිටියන් සඳහා නියමිත ප්‍රමිතියෙන් යුතු වැඩිහිටි නිවාස පවත්වාගෙන යාම',
        'short_desc_ta' => 'முதியோர்களுக்கான தரமான முதியோர் இல்லங்களை நடத்துதல்',
        'icon_class' => 'fa-house-user',
        'icon_bg' => 'linear-gradient(135deg, #1e40af, #3b82f6)',
        'bullets_en' => "Inspection and licensing of registered elders homes\nImplementation of safety, nutrition and medical guidelines\nEnsuring proper care, dignity and recreational programs for seniors",
        'bullets_si' => "වයඹ පළාතේ ලියාපදිංචි වැඩිහිටි නිවාස අධීක්ෂණය සහ බලපත්‍ර ලබාදීම\nආරක්ෂණ මාර්ගෝපදේශ, පෝෂණය සහ සෞඛ්‍ය ප්‍රමිතීන් ක්‍රියාත්මක කිරීම\nවැඩිහිටියන්ගේ ගෞරවය, සෞඛ්‍යය සහ විනෝදාත්මක පහසුකම් සහතික කිරීම",
        'bullets_ta' => "பதிவுசெய்யப்பட்ட முதியோர் இல்லங்களை ஆய்வு செய்தல் மற்றும் உரிமம் வழங்குதல்\nபாதுகாப்பு, ஊட்டச்சத்து மற்றும் மருத்துவ வழிகாட்டுதல்களை செயல்படுத்துதல்\nமுதியோர்களுக்கான சரியான பராமரிப்பு, கண்ணியம் மற்றும் பொழுதுபோக்கு திட்டங்களை உறுதி செய்தல்",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 6
    ],
    [
        'id' => 7,
        'title_en' => 'Services for Disabled Persons',
        'title_si' => 'ආබාධිත පුද්ගලයින් සඳහා සේවා',
        'title_ta' => 'மாற்றுத்திறனாளிகளுக்கான சேவைகள்',
        'short_desc_en' => 'Providing rehabilitation, assistive devices, and skills training for disabled people',
        'short_desc_si' => 'ආබාධ සහිත පුද්ගලයින් සඳහා නියමිත ප්‍රමිතියෙන් යුතු නිවාස, ආයතන, නිපුණතා සංවර්ධන මධ්‍යස්ථාන පිහිටුවා පවත්වාගෙන යාම, ඔවුන් පුනරුත්ථාපනය හා සංවර්ධනය කිරීම',
        'short_desc_ta' => 'மாற்றுத்திறனாளிகளுக்கான மறுவாழ்வு மற்றும் பயிற்சி சேவைகள்',
        'icon_class' => 'fa-wheelchair',
        'icon_bg' => 'linear-gradient(135deg, #0e7490, #06b6d4)',
        'bullets_en' => "Vocational training and livelihood skill development centers\nDisbursement of assistive devices (wheelchairs, hearing aids)\nManagement of standard residential facilities and care centers",
        'bullets_si' => "වෘත්තීය පුහුණු හා ජීවනෝපාය නිපුණතා සංවර්ධන මධ්‍යස්ථාන පිහිටුවීම\nරෝද පුටු, ශ්‍රවණ උපකරණ ඇතුළු සහායක උපකරණ ලබාදීම\nප්‍රමිතිගත නේවාසික පහසුකම් සහ පුනරුත්ථාපන වැඩසටහන් ක්‍රියාත්මක කිරීම",
        'bullets_ta' => "தொழில் பயிற்சி மற்றும் வாழ்வாதார திறன் மேம்பாட்டு மையங்கள்\nஉதவி உபகரணங்கள் வழங்குதல் (சக்கர நாற்காலிகள், காது கேட்கும் கருவிகள்)\nநிலையான குடியிருப்பு வசதிகள் மற்றும் பராமரிப்பு மையங்களின் மேலாண்மை",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 7
    ],
    [
        'id' => 8,
        'title_en' => 'Elderly Welfare Projects',
        'title_si' => 'වැඩිහිටි සුබසාධන ව්‍යාපෘති',
        'title_ta' => 'முதியோர் நலத் திட்டங்கள்',
        'short_desc_en' => 'Empowering elderly societies and funding provincial welfare programs',
        'short_desc_si' => 'වයඹ පළාත තුළ වෙසෙන වැඩිහිටි ප්‍රජාව සංවිධානගත කර වැඩිහිටි සුබසාධනය උදෙසා ව්‍යාපෘති ක්‍රියාත්මක කිරීම',
        'short_desc_ta' => 'மாகாண முதியோர்களை ஒருங்கிணைத்து நலத் திட்டங்களை செயல்படுத்தல்',
        'icon_class' => 'fa-users',
        'icon_bg' => 'linear-gradient(135deg, #7c3aed, #a78bfa)',
        'bullets_en' => "Establishing and supporting divisional and provincial elders councils\nIssuance of national/provincial elders identity cards\nOrganizing community health clinics, workshops, and religious programs",
        'bullets_si' => "ප්‍රාදේශීය සහ පළාත් වැඩිහිටි බලමණ්ඩල පිහිටුවීම සහ සහාය දීම\nජාතික සහ පළාත් වැඩිහිටි හැඳුනුම්පත් නිකුත් කිරීම\nප්‍රජා සෞඛ්‍ය සායන, සම්මන්ත්‍රණ සහ ආගමික වැඩසටහන් සංවිධානය කිරීම",
        'bullets_ta' => "பிரிவு மற்றும் மாகாண முதியோர் சபைகளை நிறுவுதல் மற்றும் ஆதரவளித்தல்\nதேசிய/மாகாண முதியோர் அடையாள அட்டைகளை வழங்குதல்\nசமூக சுகாதார மருத்துவமனைகள், பட்டறைகள் மற்றும் ஆன்மீக நிகழ்ச்சிகளை ஏற்பாடு செய்தல்",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 8
    ],
    [
        'id' => 9,
        'title_en' => 'Maintenance Grants',
        'title_si' => 'නඩත්තු ආධාර ලබාදීම',
        'title_ta' => 'பராமரிப்பு மானியம்',
        'short_desc_en' => 'Financial support for institutionalized elders or disabled court-ordered dependents',
        'short_desc_si' => 'අධ්‍යක්ෂවරයාගේ අනුමැතියෙන් හෝ අධිකරණ නියෝගයක් මඟින් හෝ ලියාපදිංචි හෝ ස්වේච්ඡා පදනමකින් ක්‍රියාත්මක වන නිවාසයක් වෙත ඇතුළත් කරනු ලබන වැඩිහිටියන් හෝ ආබාධිත පුද්ගලයින් වෙනුවෙන් නඩත්තු ආධාර ලබාදීම',
        'short_desc_ta' => 'நீதிமன்ற உத்தரவின்படி சேர்க்கப்பட்ட முதியவர்களுக்கான பராமரிப்பு உதவி',
        'icon_class' => 'fa-balance-scale',
        'icon_bg' => 'linear-gradient(135deg, #9a3412, #f97316)',
        'bullets_en' => "Financial maintenance grants based on director/court approvals\nDaily/monthly support payments for residents in voluntary care homes\nImmediate processing and release of maintenance allocations",
        'bullets_si' => "අධ්‍යක්ෂ අනුමැතිය හෝ උසාවි නියෝග මත නඩත්තු දීමනා ගෙවීම\nලියාපදිංචි ස්වේච්ඡා නිවාසවල නේවාසිකයන් සඳහා නඩත්තු ගෙවීම්\nනඩත්තු ප්‍රතිපාදන කඩිනමින් සැකසීම සහ නිදහස් කිරීම",
        'bullets_ta' => "இயக்குனர்/நீيةமன்ற ஒப்புதல்களின் அடிப்படையில் நிதி பராமரிப்பு மானியங்கள்\nதானாக முன்வந்து அமைக்கப்பட்ட பராமரிப்பு இல்லங்களில் வசிப்பவர்களுக்கான தினசரி/மாதாந்திர உதவித் தொகைகள்\nபராமரிப்பு ஒதுக்கீடுகளை உடனடியாக செயலாக்குதல் மற்றும் வெளியிடுதல்",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 9
    ],
    [
        'id' => 10,
        'title_en' => 'Regulated Institutional Supervision',
        'title_si' => 'ආයතන අධීක්ෂණය',
        'title_ta' => 'நிறுவன கண்காணிப்பு',
        'short_desc_en' => 'Regulating and inspecting social welfare homes and centers',
        'short_desc_si' => 'වැඩිහිටි පුද්ගලයින් හා අබාධිත පුද්ගලයින් නේවාසිකව තබා ගන්නා හෝ එම අය වෙනුවෙන් සේවා සපයන හෝ වයඹ පළාත තුළ පිහිටි සියලුම ආයතන අධීක්ෂණය',
        'short_desc_ta' => 'மாகாண முதியோர் மற்றும் மாற்றுத்திறனாளி இல்லங்களை கண்காணித்தல்',
        'icon_class' => 'fa-building',
        'icon_bg' => 'linear-gradient(135deg, #374151, #6b7280)',
        'bullets_en' => "Inspecting structural resources, sanitation, and safety standards\nEvaluating staff capacities and service quality metrics\nEnsuring legal registration compliance and annual license renewals",
        'bullets_si' => "නේවාසික ස්ථානවල භෞතික සම්පත්, සනීපාරක්ෂක හා ආරක්ෂණ ප්‍රමිතීන් පරීක්ෂා කිරීම\nකාර්ය මණ්ඩල ධාරිතාවය සහ සේවා ගුණාත්මකභාවය ඇගයීම\nනීත්‍යානුකූල ලියාපදිංචිය සහ වාර්ෂික බලපත්‍ර අලුත් කිරීම් සහතික කිරීම",
        'bullets_ta' => "கட்டமைப்பு வளங்கள், சுகாதாரம் மற்றும் பாதுகாப்பு தரங்களை ஆய்வு செய்தல்\nபணியாளர்களின் திறன் மற்றும் சேவை தர அளவீடுகளை மதிப்பீடு செய்தல்\nசட்டப்பூர்வ பதிவு இணக்கம் மற்றும் ஆண்டு உரிம புதுப்பிப்புகளை உறுதி செய்தல்",
        'long_desc_en' => '',
        'long_desc_si' => '',
        'long_desc_ta' => '',
        'sort_order' => 10
    ]
];

$success_count = 0;
$fail_count = 0;

foreach ($default_services as $s) {
    $id = intval($s['id']);
    $title_en = $conn->real_escape_string($s['title_en']);
    $title_si = $conn->real_escape_string($s['title_si']);
    $title_ta = $conn->real_escape_string($s['title_ta']);
    $short_desc_en = $conn->real_escape_string($s['short_desc_en']);
    $short_desc_si = $conn->real_escape_string($s['short_desc_si']);
    $short_desc_ta = $conn->real_escape_string($s['short_desc_ta']);
    $icon_class = $conn->real_escape_string($s['icon_class']);
    $icon_bg = $conn->real_escape_string($s['icon_bg']);
    $bullets_en = $conn->real_escape_string($s['bullets_en']);
    $bullets_si = $conn->real_escape_string($s['bullets_si']);
    $bullets_ta = $conn->real_escape_string($s['bullets_ta']);
    $long_desc_en = $conn->real_escape_string($s['long_desc_en']);
    $long_desc_si = $conn->real_escape_string($s['long_desc_si']);
    $long_desc_ta = $conn->real_escape_string($s['long_desc_ta']);
    $sort_order = intval($s['sort_order']);

    // Check if service with this ID already exists
    $check = $conn->query("SELECT id FROM services WHERE id = $id");
    
    if ($check && $check->num_rows > 0) {
        // Exists, update it to sync the newest properties safely
        $sql = "UPDATE services SET 
                title_en = '$title_en', title_si = '$title_si', title_ta = '$title_ta', 
                short_desc_en = '$short_desc_en', short_desc_si = '$short_desc_si', short_desc_ta = '$short_desc_ta', 
                icon_class = '$icon_class', icon_bg = '$icon_bg', 
                bullets_en = '$bullets_en', bullets_si = '$bullets_si', bullets_ta = '$bullets_ta', 
                long_desc_en = '$long_desc_en', long_desc_si = '$long_desc_si', long_desc_ta = '$long_desc_ta', 
                sort_order = $sort_order 
                WHERE id = $id";
                
        if ($conn->query($sql) === TRUE) {
            $success_count++;
            echo '<div class="log-entry info">🔄 Service ID <code>' . $id . '</code> already existed. Updated with latest data.</div>';
        } else {
            $fail_count++;
            echo '<div class="log-entry error">❌ Error updating service ID ' . $id . ': ' . htmlspecialchars($conn->error) . '</div>';
        }
    } else {
        // Does not exist, insert it
        $sql = "INSERT INTO services (id, title_en, title_si, title_ta, short_desc_en, short_desc_si, short_desc_ta, icon_class, icon_bg, bullets_en, bullets_si, bullets_ta, long_desc_en, long_desc_si, long_desc_ta, sort_order) 
                VALUES ($id, '$title_en', '$title_si', '$title_ta', '$short_desc_en', '$short_desc_si', '$short_desc_ta', '$icon_class', '$icon_bg', '$bullets_en', '$bullets_si', '$bullets_ta', '$long_desc_en', '$long_desc_si', '$long_desc_ta', $sort_order)";
                
        if ($conn->query($sql) === TRUE) {
            $success_count++;
            echo '<div class="log-entry success">➕ Service ID <code>' . $id . '</code> ("' . htmlspecialchars($s['title_en']) . '") inserted successfully.</div>';
        } else {
            $fail_count++;
            echo '<div class="log-entry error">❌ Error inserting service ID ' . $id . ': ' . htmlspecialchars($conn->error) . '</div>';
        }
    }
}

echo '<h2>🎉 Diagnostics & Seeding Summary</h2>';
if ($fail_count === 0) {
    echo '<div class="log-entry success">🏁 <strong>All operations completed successfully!</strong> ' . $success_count . ' services are fully synchronized and seeded in your database.</div>';
    echo '<p style="color:green; font-weight:600;">You can now check the homepage grid or refresh your admin settings panel. Everything will be populated automatically!</p>';
} else {
    echo '<div class="log-entry warning">⚠ Completed with ' . $fail_count . ' failures and ' . $success_count . ' successes. See logs above for details.</div>';
}

echo '<a href="members.html" class="btn">Go to Secure Admin CMS</a>';
echo '</div></body></html>';

$conn->close();
?>
