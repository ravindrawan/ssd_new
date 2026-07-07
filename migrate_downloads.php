<?php
/**
 * Database Migration Script for Download/Application Form Links
 * Place this file on your web server and access it via browser to execute:
 * e.g., http://your-domain/ssdweb/migrate_downloads.php
 */

header('Content-Type: text/html; charset=utf-8');
require_once 'db_config.php';

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Downloads Link Migrator</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Outfit", sans-serif; background: #f1f5f9; padding: 40px 20px; color: #1e293b; line-height: 1.5; }
        .card { max-width: 750px; margin: 0 auto; background: white; border-radius: 16px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h1 { color: #1e3a5f; font-size: 1.8rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .log-entry { font-family: monospace; font-size: 0.9rem; padding: 10px 15px; border-radius: 6px; margin-bottom: 8px; border-left: 4px solid #cbd5e1; }
        .success { background: #dcfce7; color: #15803d; border-left-color: #22c55e; }
        .info { background: #e0f2fe; color: #0369a1; border-left-color: #0ea5e9; }
        .warning { background: #fef3c7; color: #b45309; border-left-color: #f59e0b; }
        .error { background: #fee2e2; color: #b91c1c; border-left-color: #ef4444; }
        .btn { display: inline-block; background: #1e3a5f; color: white; padding: 10px 25px; border-radius: 30px; text-decoration: none; font-weight: 500; margin-top: 20px; transition: 0.2s; }
        .btn:hover { background: #111827; }
    </style>
</head>
<body>
<div class="card">
    <h1>📂 Download Links Unicode to ASCII Migrator</h1>
    <p>This script repairs file download links in the database to prevent 404 Not Found errors on Windows/Apache servers.</p>';

if (isset($db_connection_error) && $db_connection_error !== null) {
    echo '<div class="log-entry error">❌ <strong>Database Connection Failed:</strong> ' . htmlspecialchars($db_connection_error) . '</div>';
    echo '<p>Please check your database credentials in <code>db_config.php</code>.</p>';
    echo '</div></body></html>';
    exit;
}

echo '<div class="log-entry success">✔ <strong>Connected successfully</strong> to database: <code>' . htmlspecialchars($db_name) . '</code></div>';

$mapping = [
    // Sinhala
    'forms/අධ්‍යාපන ශිෂ්‍යාධාර සිංහල.pdf' => 'forms/educational_scholarship_si.pdf',
    'forms/අනියම් සහනාධාර සිංහල.pdf' => 'forms/casual_relief_si.pdf',
    'forms/නිවාස ගත රැකවරණ්‍ය.pdf' => 'forms/residential_care_si.pdf',
    'forms/වල් අලි සිංහල.pdf' => 'forms/elephant_damage_si.pdf',
    'forms/විදුහල්පති නිර්දේශය සිංහල.pdf' => 'forms/principal_recommendation_si.pdf',
    'forms/වෛද්‍යාධාර සිංහල.pdf' => 'forms/medical_aid_si.pdf',
    'forms/සිසුමිණ ග්‍රාම නිලධාරී වාර්තාව.pdf' => 'forms/sisumina_gn_report_si.pdf',
    'forms/හදිසි අනතුරු සිංහල.pdf' => 'forms/accident_aid_si.pdf',
    'forms/හදිසි සහන සිංහල.pdf' => 'forms/casual_relief_si.pdf',
    'forms/වැඩිහිටි නිවාස ඇතුලත් කිරීම සිංහල.pdf' => 'forms/residential_care_si.pdf',

    // Tamil
    'forms/අධ්‍යාපන ශිෂ්‍යාධාර දෙමළ.pdf' => 'forms/educational_scholarship_ta.pdf',
    'forms/අනියම් සහනාධාර දෙමළ.pdf' => 'forms/casual_relief_ta.pdf',
    'forms/වල් අලි දෙමළ.pdf' => 'forms/elephant_damage_ta.pdf',
    'forms/විදුහල්පති නිර්දේශය දෙමළ.pdf' => 'forms/principal_recommendation_ta.pdf',
    'forms/වෛද්‍යාධාර දෙමළ.pdf' => 'forms/medical_aid_ta.pdf',
    'forms/සිසුමිණ ග්‍රාම නිලධාරී දෙමළ.pdf' => 'forms/sisumina_gn_report_ta.pdf',
    'forms/සිසුමිණ ග්‍රාම නිලධාරී දෙමළ.pdf' => 'forms/sisumina_gn_report_ta.pdf',
    'forms/හදිසි අනතුරු දෙමළ.pdf' => 'forms/accident_aid_ta.pdf',
    'forms/tamil/கல்வி உதவித்தொகை தமிழ்.pdf' => 'forms/educational_scholarship_ta.pdf',
    'forms/tamil/அவசர நிவாரண உதவி தமிழ்.pdf' => 'forms/casual_relief_ta.pdf',
    'forms/tamil/இல்லவாசிகளுக்கான சேர்க்கை தமிழ்.pdf' => 'forms/residential_care_si.pdf'
];

$res = $conn->query("SELECT * FROM downloads");
$updated_count = 0;

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $id = $row['id'];
        $title = $row['title'];
        $updates = [];

        foreach (['file_url', 'file_url_si', 'file_url_ta'] as $col) {
            $val = $row[$col];
            $normalized_val = str_replace("\xc2\xa0", " ", $val); // normalize non-breaking spaces

            foreach ($mapping as $key => $target) {
                $normalized_key = str_replace("\xc2\xa0", " ", $key);
                if ($val === $key || $normalized_val === $normalized_key || trim($val) === trim($key)) {
                    $updates[$col] = $target;
                    break;
                }
            }
        }

        if (!empty($updates)) {
            $sets = [];
            foreach ($updates as $col => $target) {
                $sets[] = "$col = '" . $conn->real_escape_string($target) . "'";
                echo '<div class="log-entry info">🔧 <strong>' . htmlspecialchars($title) . ' (ID: ' . $id . ')</strong> - Field <code>' . $col . '</code>: <br>';
                echo '<span style="color:#b91c1c; text-decoration:line-through;">' . htmlspecialchars($row[$col]) . '</span> ➔ ';
                echo '<span style="color:#15803d; font-weight:600;">' . htmlspecialchars($target) . '</span></div>';
            }
            $update_sql = "UPDATE downloads SET " . implode(", ", $sets) . " WHERE id = $id";
            if ($conn->query($update_sql) === TRUE) {
                $updated_count++;
            } else {
                echo '<div class="log-entry error">❌ Database update failed: ' . htmlspecialchars($conn->error) . '</div>';
            }
        }
    }
    
    if ($updated_count > 0) {
        echo '<div class="log-entry success" style="margin-top:20px;">✔ <strong>Success:</strong> Successfully migrated ' . $updated_count . ' documents to ASCII filenames!</div>';
    } else {
        echo '<div class="log-entry info" style="margin-top:20px;">ℹ <strong>Information:</strong> All download links are already using ASCII filenames. No updates needed.</div>';
    }
} else {
    echo '<div class="log-entry error">❌ Query failed: ' . htmlspecialchars($conn->error) . '</div>';
}

$conn->close();
echo '<a href="index.html" class="btn">Go to Home</a>
</div>
</body>
</html>';
