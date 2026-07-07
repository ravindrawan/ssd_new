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
        $sql = "SELECT id, title, publish_date, file_url, status FROM procurements ORDER BY publish_date DESC, id DESC";
        $result = $conn->query($sql);
        $procurements = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $procurements[] = $row;
            }
        }
        echo json_encode(["status" => "success", "procurements" => $procurements]);
        break;

    case 'POST':
        if (!isset($_POST['title']) || !isset($_POST['publish_date'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (title, publish_date)"]);
            exit;
        }

        $title = $conn->real_escape_string($_POST['title']);
        $publish_date = $conn->real_escape_string($_POST['publish_date']);
        $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : 'active';
        $file_url = isset($_POST['file_url']) && !empty($_POST['file_url']) ? $conn->real_escape_string($_POST['file_url']) : '#';
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE procurements SET title = '$title', publish_date = '$publish_date', file_url = '$file_url', status = '$status' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Procurement notice updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating procurement: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO procurements (title, publish_date, file_url, status) VALUES ('$title', '$publish_date', '$file_url', '$status')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Procurement notice added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding procurement: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM procurements WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Procurement notice deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting procurement: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
