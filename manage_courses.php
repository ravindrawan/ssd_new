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
        $sql = "SELECT id, category, title, event_date, location, icon_class, url FROM courses_events ORDER BY event_date DESC, id DESC";
        $result = $conn->query($sql);
        $courses = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }
        echo json_encode(["status" => "success", "courses" => $courses]);
        break;

    case 'POST':
        if (!isset($_POST['category']) || !isset($_POST['title']) || !isset($_POST['event_date']) || !isset($_POST['location'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (category, title, event_date, location)"]);
            exit;
        }

        $category = $conn->real_escape_string($_POST['category']);
        $title = $conn->real_escape_string($_POST['title']);
        $event_date = $conn->real_escape_string($_POST['event_date']);
        $location = $conn->real_escape_string($_POST['location']);
        $icon_class = isset($_POST['icon_class']) && !empty($_POST['icon_class']) ? $conn->real_escape_string($_POST['icon_class']) : 'fa-calendar-alt';
        $url = isset($_POST['url']) && !empty($_POST['url']) ? $conn->real_escape_string($_POST['url']) : '#';
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE courses_events SET category = '$category', title = '$title', event_date = '$event_date', location = '$location', icon_class = '$icon_class', url = '$url' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Workshop updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating workshop: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO courses_events (category, title, event_date, location, icon_class, url) VALUES ('$category', '$title', '$event_date', '$location', '$icon_class', '$url')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Workshop added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding workshop: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM courses_events WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Workshop deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting workshop: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
