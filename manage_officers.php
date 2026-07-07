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

// Auto-migration: check if sort_order column exists, if not, add it
$check_cols = $conn->query("SHOW COLUMNS FROM officers LIKE 'sort_order'");
if ($check_cols && $check_cols->num_rows == 0) {
    $conn->query("ALTER TABLE officers ADD COLUMN sort_order INT DEFAULT 0");
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT id, name, title, phone, category, division, email, photo_url, sort_order FROM officers ORDER BY sort_order ASC, id ASC";
        $result = $conn->query($sql);
        $officers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $officers[] = $row;
            }
        }
        echo json_encode(["status" => "success", "officers" => $officers]);
        break;

    case 'POST':
        if (!isset($_POST['name']) || !isset($_POST['title']) || !isset($_POST['phone'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (name, title, phone)"]);
            exit;
        }

        $name = $conn->real_escape_string($_POST['name']);
        $title = $conn->real_escape_string($_POST['title']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
        $category = isset($_POST['category']) ? $conn->real_escape_string($_POST['category']) : 'hq';
        $division = isset($_POST['division']) ? $conn->real_escape_string($_POST['division']) : 'Head Office';
        $photo_url = isset($_POST['photo_url']) ? $conn->real_escape_string($_POST['photo_url']) : '';
        $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE officers SET name = '$name', title = '$title', phone = '$phone', email = '$email', category = '$category', division = '$division', photo_url = '$photo_url', sort_order = $sort_order WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Officer updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating officer details: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO officers (name, title, phone, email, category, division, photo_url, sort_order) VALUES ('$name', '$title', '$phone', '$email', '$category', '$division', '$photo_url', $sort_order)";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Officer details added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding officer: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM officers WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Officer details removed successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error removing officer: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
