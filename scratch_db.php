<?php
require 'db_config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Connected successfully to database.\n";

$services = [
    1 => [
        'title_si' => 'මහජනාධාර ලබාදීම',
        'short_desc_si' => 'අඩු ආදායම්ලාභී ප්රජාව සඳහා මහජනාධාර ලබාදීම',
        'title_en' => 'Public Assistance',
        'short_desc_en' => 'Provision of public assistance for low-income communities'
    ],
    2 => [
        'title_si' => 'සිසුමිණ ශිෂ්යාධාර ලබාදිම',
        'short_desc_si' => 'වැන්දඹු, විසුරුණු, රෝගී ආබාධ සහිත හා අසරණ පවුල් වල දරුවන් සඳහා සිසුමිණ ශිෂ්යාධාර ලබාදීම (අධ්යාපන ආධාර)',
        'title_en' => 'Sisumina Scholarship',
        'short_desc_en' => 'Educational assistance for children from vulnerable, widowed, or disabled families'
    ],
    3 => [
        'title_si' => 'පිළිසරණීය නිවාස ආධාර ලබාදීම',
        'short_desc_si' => 'අඩු ආදායම්ලාභී පවුල් සඳහා පිළිසරණීය නිවාස ආධාර ලබාදීම',
        'title_en' => 'Housing Assistance',
        'short_desc_en' => 'Providing housing construction and repair grants to low-income families'
    ],
    4 => [
        'title_si' => 'ලාදුරු රෝගය සඳහා ආධාර ගෙවීම',
        'short_desc_si' => 'ලාදුරු රෝගය වැළදුනු අඩු ආදායම්ලාභීන් සඳහා වෛද්ය නිර්දේශය මත ආධාර ගෙවීම',
        'title_en' => 'Leprosy Patient Assistance',
        'short_desc_en' => 'Monthly financial medical assistance for registered leprosy patients'
    ],
    5 => [
        'title_si' => 'විශේෂ වෛද්යාධාර ගෙවීම',
        'short_desc_si' => 'দীර්ඝ කාලීනව ප්රතිකාර ලබාගත යුතු බවට වෛද්ය කමිටුවක නිර්දේශය ලැබූ රෝගීන් සඳහා විශේෂ වෛද්යාධාර ගෙවීම',
        'title_en' => 'Special Medical Assistance',
        'short_desc_en' => 'Assistance for patients requiring long-term medical treatments and drugs'
    ],
    6 => [
        'title_si' => 'වැඩිහිටි නිවාස පවත්වාගනෙ යාම',
        'short_desc_si' => 'වැඩිහිටියන් සඳහා නියමිත ප්රමිතියෙන් යුතු වැඩිහිටි නිවාස පවත්වාගෙන යාම',
        'title_en' => 'Elders Homes Management',
        'short_desc_en' => 'Monitoring and maintaining standards of registered elder care homes'
    ],
    7 => [
        'title_si' => 'ආබාධිත පුද්ගලයින් සඳහා සේවා',
        'short_desc_si' => 'ආබාධ සහිත පුද්ගලයින් සඳහා නියමිත ප්රමිතියෙන් යුතු නිවාස, ආයතන, නිපුණතා සංවර්ධන මධ්යස්ථාන පිහිටුවා පවත්වාගෙන යාම, ඔවුන් පුනරුත්ථාපනය හා සංවර්ධනය කිරීම',
        'title_en' => 'Disability Services & Rehabilitation',
        'short_desc_en' => 'Providing rehabilitation, assistive devices, and skills training for disabled people'
    ],
    8 => [
        'title_si' => 'වැඩිහිටි සුබසාධන ව්යාපෘති',
        'short_desc_si' => 'වයඹ පළාත තුළ වෙසෙන වැඩිහිටි ප්රජාව සංවිධානගත කර වැඩිහිටි සුබසාධනය උදෙසා ව්යාපෘති ක්රියාත්මක කිරීම',
        'title_en' => 'Elderly Welfare Schemes',
        'short_desc_en' => 'Empowering elderly societies and funding provincial welfare programs'
    ],
    9 => [
        'title_si' => 'නඩත්තු ආධාර ලබාදීම',
        'short_desc_si' => 'අධ්යක්ෂවරයාගේ අනුමැතියෙන් හෝ අධිකරණ නියෝගයක් මඟින් හෝ ලියාපදිංචි හෝ ස්වේච්ඡා පදනමකින් ක්රියාත්මක වන නිවාසයක් වෙත ඇතුළත් කරනු ලබන වැඩිහිටියන් හෝ ආබාධිත පුද්ගලයින් වෙනුවෙන් නඩත්තු ආධාර ලබාදීම',
        'title_en' => 'Maintenance Grants',
        'short_desc_en' => 'Financial support for institutionalized elders or disabled court-ordered dependents'
    ],
    10 => [
        'title_si' => 'ආයතන අධීක්ෂණය',
        'short_desc_si' => 'වැඩිහිටි පුද්ගලයින් හා අබාධිත පුද්ගලයින් නේවාසිකව තබා ගන්නා හෝ එම අය වෙනුවෙන් සේවා සපයන හෝ වයඹ පළාත තුළ පිහිටි සියලුම ආයතන අධීක්ෂණය',
        'title_en' => 'Regulated Institutional Supervision',
        'short_desc_en' => 'Regulating and inspecting social welfare homes and centers'
    ]
];

foreach ($services as $id => $s) {
    $t_si = $conn->real_escape_string($s['title_si']);
    $d_si = $conn->real_escape_string($s['short_desc_si']);
    $t_en = $conn->real_escape_string($s['title_en']);
    $d_en = $conn->real_escape_string($s['short_desc_en']);
    
    $sql = "UPDATE services SET 
            title_si = '$t_si', short_desc_si = '$d_si',
            title_en = '$t_en', short_desc_en = '$d_en'
            WHERE id = $id";
            
    if ($conn->query($sql) === TRUE) {
        echo "Updated service $id successfully.\n";
    } else {
        echo "Error updating service $id: " . $conn->error . "\n";
    }
}

$conn->close();
?>
