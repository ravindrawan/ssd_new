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
        $sql = "SELECT id, category, title, news_date, content, image_url, image_before, image_after, url FROM news ORDER BY news_date DESC, id DESC";
        $result = $conn->query($sql);
        $news = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $news[] = $row;
            }
        }
        echo json_encode(["status" => "success", "news" => $news]);
        break;

    case 'POST':
        if (!isset($_POST['category']) || !isset($_POST['title']) || !isset($_POST['content'])) {
            echo json_encode(["status" => "error", "message" => "Missing required fields (category, title, content)"]);
            exit;
        }

        $category = $conn->real_escape_string($_POST['category']);
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $news_date = isset($_POST['news_date']) && !empty($_POST['news_date']) ? $conn->real_escape_string($_POST['news_date']) : date('Y-m-d');
        $url = isset($_POST['url']) && !empty($_POST['url']) ? $conn->real_escape_string($_POST['url']) : '#';
        
        $image_url = isset($_POST['image_url']) ? $conn->real_escape_string($_POST['image_url']) : '';
        $image_before = isset($_POST['image_before']) ? $conn->real_escape_string($_POST['image_before']) : '';
        $image_after = isset($_POST['image_after']) ? $conn->real_escape_string($_POST['image_after']) : '';
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id > 0) {
            // Update
            $sql = "UPDATE news SET category = '$category', title = '$title', content = '$content', news_date = '$news_date', url = '$url', image_url = '$image_url', image_before = '$image_before', image_after = '$image_after' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "News article updated successfully", "id" => $id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating news article: " . $conn->error]);
            }
        } else {
            // Insert
            $sql = "INSERT INTO news (category, title, news_date, content, url, image_url, image_before, image_after) VALUES ('$category', '$title', '$news_date', '$content', '$url', '$image_url', '$image_before', '$image_after')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "News article added successfully", "id" => $conn->insert_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error adding news article: " . $conn->error]);
            }
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
             echo json_encode(["status" => "error", "message" => "Missing ID"]);
             exit;
        }
        $id = intval($_GET['id']);
        $sql = "DELETE FROM news WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "News article deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting news article: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
?>
