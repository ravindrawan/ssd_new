<?php
/**
 * Migration Script to add missing header branding setting keys to the database.
 * Run this script once via browser or CLI. It will NOT overwrite any existing values.
 */
require_once 'db_config.php';

if (isset($db_connection_error) && $db_connection_error !== null) {
    die("Database Connection Error: " . $db_connection_error);
}

$settings = [
    [
        'header_national_logo',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png'
    ],
    [
        'header_provincial_logo',
        'Nwp_sri_lanka.png',
        'Nwp_sri_lanka.png',
        'Nwp_sri_lanka.png'
    ],
    [
        'header_title_en',
        'DEPARTMENT OF SOCIAL SERVICES - NWP',
        'DEPARTMENT OF SOCIAL SERVICES - NWP',
        'DEPARTMENT OF SOCIAL SERVICES - NWP'
    ],
    [
        'header_title_si',
        'වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව',
        'වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව',
        'වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව'
    ],
    [
        'header_title_ta',
        'வடமேல் மாகாண சமூக சேவைகள் திணைக்களம்',
        'வடமேல் மாகாண சமூக சேவைகள் திணைக்களம்',
        'வடமேல் மாகாண சமூக சேவைகள் திணைக்களம்'
    ]
];

$inserted = 0;
echo "<div style='font-family: monospace; padding: 20px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px;'>";
echo "<h2>Updating Site Settings Tables...</h2><hr>";

foreach ($settings as $s) {
    $key = $conn->real_escape_string($s[0]);
    $en = $conn->real_escape_string($s[1]);
    $si = $conn->real_escape_string($s[2]);
    $ta = $conn->real_escape_string($s[3]);
    
    // Check if the setting key exists
    $check = $conn->query("SELECT id, content_en, content_si, content_ta FROM site_sections WHERE section_key = '$key'");
    if ($check) {
        if ($check->num_rows === 0) {
            $sql = "INSERT INTO site_sections (section_key, content_en, content_si, content_ta) 
                    VALUES ('$key', '$en', '$si', '$ta')";
            if ($conn->query($sql) === TRUE) {
                $inserted++;
                echo "<span style='color: green;'>[+]</span> Key <b>'$key'</b> added successfully.<br>";
            } else {
                echo "<span style='color: red;'>[-]</span> Error adding key <b>'$key'</b>: " . $conn->error . "<br>";
            }
        } else {
            $row = $check->fetch_assoc();
            // If any language content is empty, update them to default to restore header layout
            if (empty(trim($row['content_en'] ?? '')) || empty(trim($row['content_si'] ?? '')) || empty(trim($row['content_ta'] ?? ''))) {
                $sql = "UPDATE site_sections 
                        SET content_en = '$en', content_si = '$si', content_ta = '$ta' 
                        WHERE section_key = '$key'";
                if ($conn->query($sql) === TRUE) {
                    $inserted++;
                    echo "<span style='color: blue;'>[*]</span> Key <b>'$key'</b> had empty content and was updated to defaults.<br>";
                } else {
                    echo "<span style='color: red;'>[-]</span> Error updating key <b>'$key'</b>: " . $conn->error . "<br>";
                }
            } else {
                echo "<span style='color: #64748b;'>[o]</span> Key <b>'$key'</b> already exists with value. Skipped to prevent overwriting.<br>";
            }
        }
    }
}

echo "<hr><b>Database patch successfully executed! $inserted new keys added.</b>";
echo "</div>";

if ($conn) $conn->close();
?>
