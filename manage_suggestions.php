<?php
header('Content-Type: application/json');
require_once 'db_config.php';
require_once 'session_helper.php';

if (isset($db_connection_error) && $db_connection_error !== null) {
    echo json_encode(["status" => "error", "message" => "Database Connection Error: " . $db_connection_error]);
    exit;
}

// Enforce backend authentication for suggestions inbox (both reading and deleting)
require_auth();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT id, name, email, phone, subject, message, submitted_at FROM suggestions ORDER BY submitted_at DESC, id DESC";
        $result = $conn->query($sql);
        $suggestions = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $suggestions[] = $row;
            }
        }
        echo json_encode(["status" => "success", "suggestions" => $suggestions]);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(["status" => "error", "message" => "Missing ID parameter."]);
            exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM suggestions WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Suggestion deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting suggestion: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed."]);
        break;
}

if ($conn) $conn->close();
?>
