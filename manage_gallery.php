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
        $sql = "SELECT id, title, image_url, description FROM gallery ORDER BY id DESC";
        $result = $conn->query($sql);
        $gallery = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $gallery[] = $row;
            }
        }
        echo json_encode(["status" => "success", "gallery" => $gallery]);
        break;

    case 'POST':
        if (!isset($_POST['title']) || !isset($_POST['image_url'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (title, image_url)"]);
            exit;
        }

        $title = $conn->real_escape_string($_POST['title']);
        $image_url = $conn->real_escape_string($_POST['image_url']);
        $description = isset($_POST['description']) ? $conn->real_escape_string($_POST['description']) : '';
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE gallery SET title = '$title', image_url = '$image_url', description = '$description' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Gallery image updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating gallery image: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO gallery (title, image_url, description) VALUES ('$title', '$image_url', '$description')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Gallery image added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding gallery image: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM gallery WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Gallery image deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting gallery image: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
