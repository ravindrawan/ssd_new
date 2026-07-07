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
        $sql = "SELECT id, title, image_url, sort_order FROM banners ORDER BY sort_order ASC, id ASC";
        $result = $conn->query($sql);
        $banners = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $banners[] = $row;
            }
        }
        echo json_encode(["status" => "success", "banners" => $banners]);
        break;

    case 'POST':
        if (!isset($_POST['image_url'])) {
            echo json_encode(["status" => "error", "message" => "Missing required field (image_url)"]);
            exit;
        }

        $image_url = $conn->real_escape_string($_POST['image_url']);
        $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
        $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            // Update
            $sql = "UPDATE banners SET title = '$title', image_url = '$image_url', sort_order = $sort_order WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Banner updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating banner: " . $conn->error]);
            }
        } else {
            // Insert
            $sql = "INSERT INTO banners (title, image_url, sort_order) VALUES ('$title', '$image_url', $sort_order)";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Banner added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding banner: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM banners WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Banner deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting banner: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
