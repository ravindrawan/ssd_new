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
        $sql = "SELECT id, category, title, url, image_url FROM important_links ORDER BY id ASC";
        $result = $conn->query($sql);
        $links = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $links[] = $row;
            }
        }
        echo json_encode(["status" => "success", "links" => $links]);
        break;

    case 'POST':
        if (!isset($_POST['category']) || !isset($_POST['title']) || !isset($_POST['url'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (category, title, url)"]);
            exit;
        }

        $category = $conn->real_escape_string($_POST['category']);
        $title = $conn->real_escape_string($_POST['title']);
        $url = $conn->real_escape_string($_POST['url']);
        $image_url = isset($_POST['image_url']) ? $conn->real_escape_string($_POST['image_url']) : '';
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE important_links SET category = '$category', title = '$title', url = '$url', image_url = '$image_url' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Link updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating link: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO important_links (category, title, url, image_url) VALUES ('$category', '$title', '$url', '$image_url')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Link added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding link: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM important_links WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Link deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting link: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
