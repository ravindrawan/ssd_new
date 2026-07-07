<?php
require_once 'db_config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

// 1. Add localization columns to the downloads table if they do not exist
$columns_to_add = [
    'title_si' => "VARCHAR(255) DEFAULT '' AFTER title",
    'title_ta' => "VARCHAR(255) DEFAULT '' AFTER title_si",
    'description_si' => "TEXT DEFAULT NULL AFTER description",
    'description_ta' => "TEXT DEFAULT NULL AFTER description_si",
    'file_url_si' => "VARCHAR(255) DEFAULT '' AFTER file_url",
    'file_url_ta' => "VARCHAR(255) DEFAULT '' AFTER file_url_si"
];

foreach ($columns_to_add as $col => $definition) {
    $check = $conn->query("SHOW COLUMNS FROM downloads LIKE '$col'");
    if ($check->num_rows === 0) {
        $alter_sql = "ALTER TABLE downloads ADD COLUMN $col $definition";
        if ($conn->query($alter_sql) === TRUE) {
            echo "Column '$col' added successfully.\n";
        } else {
            echo "Error adding column '$col': " . $conn->error . "\n";
        }
    } else {
        echo "Column '$col' already exists.\n";
    }
}

// 2. Seed/Insert mock localized forms
$forms = [
    [
        'title' => 'Educational Scholarship Application',
        'title_si' => 'අධ්‍යාපන ශිෂ්‍යත්ව අයදුම්පත',
        'title_ta' => 'கல்வி உதவித்தொகை விண்ணப்பம்',
        'category' => 'forms',
        'description' => 'Application form for school/university educational scholarship programs.',
        'description_si' => 'පාසල් සහ විශ්වවිද්‍යාල අධ්‍යාපන ශිෂ්‍යත්ව වැඩසටහන් සඳහා අයදුම්පත.',
        'description_ta' => 'பள்ளி மற்றும் பல்கலைக்கழக கல்வி உதவித்தொகை திட்டங்களுக்கான விண்ணப்ப படிவம்.',
        'file_url' => 'forms/educational_scholarship_si.pdf',
        'file_url_si' => 'forms/educational_scholarship_si.pdf',
        'file_url_ta' => 'forms/educational_scholarship_ta.pdf'
    ],
    [
        'title' => 'Casual Relief Assistance Form',
        'title_si' => 'හදිසි ආධාර ලබාගැනීමේ අයදුම්පත',
        'title_ta' => 'அவசர நிவாரண உதவி படிவம்',
        'category' => 'forms',
        'description' => 'Request form for casual relief grants for vulnerable families.',
        'description_si' => 'අසරණ පවුල් සඳහා වන හදිසි සහන ආධාර ලබාගැනීමේ අයදුම්පත.',
        'description_ta' => 'பாதிக்கப்படக்கூடிய குடும்பங்களுக்கான அவசர நிவாரண மானியங்களை கோரும் படிவம்.',
        'file_url' => 'forms/casual_relief_si.pdf',
        'file_url_si' => 'forms/casual_relief_si.pdf',
        'file_url_ta' => 'forms/casual_relief_ta.pdf'
    ],
    [
        'title' => 'Residential Care Admission Form',
        'title_si' => 'වැඩිහිටි නිවාස නේවාසික ඇතුළත් කිරීමේ අයදුම්පත',
        'title_ta' => 'இல்லவாசிகளுக்கான சேர்க்கை படிவம்',
        'category' => 'forms',
        'description' => 'Admission form for provincial state-run elder care homes.',
        'description_si' => 'පළාත් රාජ්‍ය වැඩිහිටි නිවාස වෙත නේවාසිකව ඇතුළත් කිරීමේ අයදුම්පත.',
        'description_ta' => 'மாகாண அரச முதியோர் இல்ல சேர்க்கை விண்ணப்ப படிவம்.',
        'file_url' => 'forms/residential_care_si.pdf',
        'file_url_si' => 'forms/residential_care_si.pdf',
        'file_url_ta' => 'forms/residential_care_si.pdf'
    ]
];


foreach ($forms as $f) {
    $title = $conn->real_escape_string($f['title']);
    $title_si = $conn->real_escape_string($f['title_si']);
    $title_ta = $conn->real_escape_string($f['title_ta']);
    $cat = $conn->real_escape_string($f['category']);
    $desc = $conn->real_escape_string($f['description']);
    $desc_si = $conn->real_escape_string($f['description_si']);
    $desc_ta = $conn->real_escape_string($f['description_ta']);
    $url = $conn->real_escape_string($f['file_url']);
    $url_si = $conn->real_escape_string($f['file_url_si']);
    $url_ta = $conn->real_escape_string($f['file_url_ta']);

    // Check if duplicate title
    $check = $conn->query("SELECT id FROM downloads WHERE title = '$title'");
    if ($check->num_rows === 0) {
        $insert_sql = "INSERT INTO downloads (title, title_si, title_ta, category, description, description_si, description_ta, file_url, file_url_si, file_url_ta) 
                       VALUES ('$title', '$title_si', '$title_ta', '$cat', '$desc', '$desc_si', '$desc_ta', '$url', '$url_si', '$url_ta')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "Seeded form '$title' successfully.\n";
        } else {
            echo "Error seeding form '$title': " . $conn->error . "\n";
        }
    } else {
        $update_sql = "UPDATE downloads SET title_si='$title_si', title_ta='$title_ta', description_si='$desc_si', description_ta='$desc_ta', file_url_si='$url_si', file_url_ta='$url_ta' WHERE title='$title'";
        if ($conn->query($update_sql) === TRUE) {
            echo "Updated form '$title' translations successfully.\n";
        } else {
            echo "Error updating form '$title': " . $conn->error . "\n";
        }
    }
}

$conn->close();
