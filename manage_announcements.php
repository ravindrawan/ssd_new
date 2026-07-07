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
        $sql = "SELECT id, category, title, url, badge, created_at FROM announcements ORDER BY id DESC";
        $result = $conn->query($sql);
        $announcements = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $announcements[] = $row;
            }
        }
        echo json_encode(["status" => "success", "announcements" => $announcements]);
        break;

    case 'POST':
        if (!isset($_POST['category']) || !isset($_POST['title'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (category, title)"]);
            exit;
        }

        $category = $conn->real_escape_string($_POST['category']);
        $title = $conn->real_escape_string($_POST['title']);
        $url = isset($_POST['url']) && !empty($_POST['url']) ? $conn->real_escape_string($_POST['url']) : '#';
        $badge = isset($_POST['badge']) ? $conn->real_escape_string($_POST['badge']) : NULL;
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE announcements SET category = '$category', title = '$title', url = '$url', badge = " . ($badge ? "'$badge'" : "NULL") . " WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Announcement updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating announcement: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO announcements (category, title, url, badge) VALUES ('$category', '$title', '$url', " . ($badge ? "'$badge'" : "NULL") . ")";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Announcement added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding announcement: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM announcements WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Announcement deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting announcement: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
