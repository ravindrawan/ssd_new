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
        $sql = "SELECT id, title_en, title_si, title_ta, short_desc_en, short_desc_si, short_desc_ta, icon_class, icon_bg, bullets_en, bullets_si, bullets_ta, long_desc_en, long_desc_si, long_desc_ta, sort_order FROM services ORDER BY sort_order ASC, id ASC";
        $result = $conn->query($sql);
        $services = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
        echo json_encode(["status" => "success", "services" => $services]);
        break;

    case 'POST':
        if (
            !isset($_POST['title_en']) || !isset($_POST['title_si']) || !isset($_POST['title_ta']) ||
            !isset($_POST['short_desc_en']) || !isset($_POST['short_desc_si']) || !isset($_POST['short_desc_ta'])
        ) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (titles or short descriptions)"]);
            exit;
        }

        $title_en = $conn->real_escape_string($_POST['title_en']);
        $title_si = $conn->real_escape_string($_POST['title_si']);
        $title_ta = $conn->real_escape_string($_POST['title_ta']);
        
        $short_desc_en = $conn->real_escape_string($_POST['short_desc_en']);
        $short_desc_si = $conn->real_escape_string($_POST['short_desc_si']);
        $short_desc_ta = $conn->real_escape_string($_POST['short_desc_ta']);
        
        $icon_class = isset($_POST['icon_class']) && !empty($_POST['icon_class']) ? $conn->real_escape_string($_POST['icon_class']) : 'fa-concierge-bell';
        $icon_bg = isset($_POST['icon_bg']) && !empty($_POST['icon_bg']) ? $conn->real_escape_string($_POST['icon_bg']) : 'linear-gradient(135deg, #1e3a5f, #2563eb)';
        
        $bullets_en = isset($_POST['bullets_en']) ? $conn->real_escape_string($_POST['bullets_en']) : '';
        $bullets_si = isset($_POST['bullets_si']) ? $conn->real_escape_string($_POST['bullets_si']) : '';
        $bullets_ta = isset($_POST['bullets_ta']) ? $conn->real_escape_string($_POST['bullets_ta']) : '';
        
        $long_desc_en = isset($_POST['long_desc_en']) ? $conn->real_escape_string($_POST['long_desc_en']) : '';
        $long_desc_si = isset($_POST['long_desc_si']) ? $conn->real_escape_string($_POST['long_desc_si']) : '';
        $long_desc_ta = isset($_POST['long_desc_ta']) ? $conn->real_escape_string($_POST['long_desc_ta']) : '';
        
        $sort_order = isset($_POST['sort_order']) && $_POST['sort_order'] !== '' ? intval($_POST['sort_order']) : 0;
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            // Update
            $sql = "UPDATE services SET 
                title_en = '$title_en', title_si = '$title_si', title_ta = '$title_ta', 
                short_desc_en = '$short_desc_en', short_desc_si = '$short_desc_si', short_desc_ta = '$short_desc_ta', 
                icon_class = '$icon_class', icon_bg = '$icon_bg', 
                bullets_en = '$bullets_en', bullets_si = '$bullets_si', bullets_ta = '$bullets_ta', 
                long_desc_en = '$long_desc_en', long_desc_si = '$long_desc_si', long_desc_ta = '$long_desc_ta', 
                sort_order = $sort_order 
                WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Service updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating service: " . $conn->error]);
            }
        } else {
            // Insert
            $sql = "INSERT INTO services (title_en, title_si, title_ta, short_desc_en, short_desc_si, short_desc_ta, icon_class, icon_bg, bullets_en, bullets_si, bullets_ta, long_desc_en, long_desc_si, long_desc_ta, sort_order) 
                    VALUES ('$title_en', '$title_si', '$title_ta', '$short_desc_en', '$short_desc_si', '$short_desc_ta', '$icon_class', '$icon_bg', '$bullets_en', '$bullets_si', '$bullets_ta', '$long_desc_en', '$long_desc_si', '$long_desc_ta', $sort_order)";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Service added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding service: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM services WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Service deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting service: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
