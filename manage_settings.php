<?php
header('Content-Type: application/json');
require_once 'db_config.php';
require_once 'session_helper.php';

if (isset($db_connection_error) && $db_connection_error !== null) {
    echo json_encode(["status" => "error", "message" => "Database Error: " . $db_connection_error]);
    exit;
}

// Enforce authentication for modifying requests (POST, DELETE)
require_auth_for_write();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT section_key, content_en, content_si, content_ta FROM site_sections";
        $result = $conn->query($sql);
        $settings = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $settings[$row['section_key']] = [
                    "en" => $row['content_en'],
                    "si" => $row['content_si'],
                    "ta" => $row['content_ta']
                ];
            }
        }
        echo json_encode(["status" => "success", "settings" => $settings]);
        break;

    case 'POST':
        $keys = [
            'news_bar', 'about_overview', 'about_objectives', 'about_achievements',
            'site_vision', 'site_mission', 'contact_address', 'contact_phone',
            'contact_email', 'contact_map_url', 'rti_officer_name', 'rti_officer_title',
            'rti_appellate_name', 'rti_appellate_title',
            'service_inv_list', 'service_eng_list', 'service_const_list', 'service_op_list', 'service_inst_list',
            'service_eng_desc', 'service_const_desc',
            'org_chart_url', 'citizen_charter_si_url', 'citizen_charter_en_url',
            'rti_app_si_url', 'rti_app_en_url', 'rti_app_ta_url',
            'social_youtube', 'social_facebook', 'contact_fax',
            'header_national_logo', 'header_provincial_logo',
            'header_title_en', 'header_title_si', 'header_title_ta'
        ];
        
        $updated = 0;
        foreach ($keys as $key) {
            if (isset($_POST[$key . '_en'])) {
                $en = $conn->real_escape_string($_POST[$key . '_en']);
                $si = $conn->real_escape_string($_POST[$key . '_si']);
                $ta = $conn->real_escape_string($_POST[$key . '_ta']);
                
                $sql = "INSERT INTO site_sections (section_key, content_en, content_si, content_ta) 
                        VALUES ('$key', '$en', '$si', '$ta') 
                        ON DUPLICATE KEY UPDATE content_en = '$en', content_si = '$si', content_ta = '$ta'";
                if ($conn->query($sql) === TRUE) {
                    $updated++;
                }
            }
        }
        echo json_encode(["status" => "success", "message" => "Settings updated successfully. $updated keys updated."]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
