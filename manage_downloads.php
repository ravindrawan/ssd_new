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
        $sql = "SELECT id, category, title, title_si, title_ta, description, description_si, description_ta, file_url, file_url_si, file_url_ta, icon_class FROM downloads ORDER BY id DESC";
        $result = $conn->query($sql);
        $downloads = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $downloads[] = $row;
            }
        }
        echo json_encode(["status" => "success", "downloads" => $downloads]);
        break;

    case 'POST':
        if (!isset($_POST['category']) || !isset($_POST['title']) || !isset($_POST['description'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (category, title, description)"]);
            exit;
        }

        $category = $conn->real_escape_string($_POST['category']);
        $title = $conn->real_escape_string($_POST['title']);
        $title_si = isset($_POST['title_si']) ? $conn->real_escape_string($_POST['title_si']) : '';
        $title_ta = isset($_POST['title_ta']) ? $conn->real_escape_string($_POST['title_ta']) : '';
        
        $description = $conn->real_escape_string($_POST['description']);
        $description_si = isset($_POST['description_si']) ? $conn->real_escape_string($_POST['description_si']) : '';
        $description_ta = isset($_POST['description_ta']) ? $conn->real_escape_string($_POST['description_ta']) : '';
        
        $file_url = isset($_POST['file_url']) && !empty($_POST['file_url']) ? $conn->real_escape_string($_POST['file_url']) : '#';
        $file_url_si = isset($_POST['file_url_si']) ? $conn->real_escape_string($_POST['file_url_si']) : '';
        $file_url_ta = isset($_POST['file_url_ta']) ? $conn->real_escape_string($_POST['file_url_ta']) : '';
        
        $icon_class = isset($_POST['icon_class']) && !empty($_POST['icon_class']) ? $conn->real_escape_string($_POST['icon_class']) : 'fa-file-alt';
        
        // Custom default icon mapping based on categories
        if ($icon_class === 'fa-file-alt') {
            if ($category === 'forms') $icon_class = 'fa-file-invoice';
            else if ($category === 'circulars') $icon_class = 'fa-book';
            else if ($category === 'rates') $icon_class = 'fa-info-circle';
            else if ($category === 'transfers') $icon_class = 'fa-users-cog';
        }

        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE downloads SET category = '$category', title = '$title', title_si = '$title_si', title_ta = '$title_ta', description = '$description', description_si = '$description_si', description_ta = '$description_ta', file_url = '$file_url', file_url_si = '$file_url_si', file_url_ta = '$file_url_ta', icon_class = '$icon_class' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Download document updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating document: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO downloads (category, title, title_si, title_ta, description, description_si, description_ta, file_url, file_url_si, file_url_ta, icon_class) VALUES ('$category', '$title', '$title_si', '$title_ta', '$description', '$description_si', '$description_ta', '$file_url', '$file_url_si', '$file_url_ta', '$icon_class')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Download document added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding document: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM downloads WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Download document deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting document: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
