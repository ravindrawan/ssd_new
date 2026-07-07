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
        $sql = "SELECT id, category, title_en, title_si, title_ta, description_en, description_si, description_ta, image_url, financial_details FROM projects ORDER BY id ASC";
        $result = $conn->query($sql);
        $projects = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $projects[] = $row;
            }
        }
        echo json_encode(["status" => "success", "projects" => $projects]);
        break;

    case 'POST':
        if (!isset($_POST['category']) || !isset($_POST['title_en']) || !isset($_POST['description_en'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (category, title_en, description_en)"]);
            exit;
        }

        $category = $conn->real_escape_string($_POST['category']);
        $title_en = $conn->real_escape_string($_POST['title_en']);
        $title_si = isset($_POST['title_si']) ? $conn->real_escape_string($_POST['title_si']) : $title_en;
        $title_ta = isset($_POST['title_ta']) ? $conn->real_escape_string($_POST['title_ta']) : $title_en;
        
        $description_en = $conn->real_escape_string($_POST['description_en']);
        $description_si = isset($_POST['description_si']) ? $conn->real_escape_string($_POST['description_si']) : $description_en;
        $description_ta = isset($_POST['description_ta']) ? $conn->real_escape_string($_POST['description_ta']) : $description_en;
        
        $image_url = isset($_POST['image_url']) ? $conn->real_escape_string($_POST['image_url']) : '';
        $financial_details = isset($_POST['financial_details']) ? $conn->real_escape_string($_POST['financial_details']) : '';
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            $sql = "UPDATE projects SET 
                        category = '$category', 
                        title_en = '$title_en', title_si = '$title_si', title_ta = '$title_ta', 
                        description_en = '$description_en', description_si = '$description_si', description_ta = '$description_ta', 
                        image_url = '$image_url', financial_details = '$financial_details' 
                    WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Welfare project updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating welfare project: " . $conn->error]);
            }
        } else {
            $sql = "INSERT INTO projects (category, title_en, title_si, title_ta, description_en, description_si, description_ta, image_url, financial_details) 
                    VALUES ('$category', '$title_en', '$title_si', '$title_ta', '$description_en', '$description_si', '$description_ta', '$image_url', '$financial_details')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Welfare project added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding welfare project: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM projects WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Welfare project deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting welfare project: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
