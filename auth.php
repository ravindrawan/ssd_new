<?php
header('Content-Type: application/json');
require_once 'db_config.php';
require_once 'session_helper.php';

if (isset($db_connection_error) && $db_connection_error !== null) {
    echo json_encode(["status" => "error", "message" => "Database Connection Error: " . $db_connection_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    if ($action === 'check') {
        if (is_authenticated()) {
            echo json_encode([
                "status" => "success",
                "authenticated" => true,
                "user" => $_SESSION['user']
            ]);
        } else {
            echo json_encode([
                "status" => "success",
                "authenticated" => false
            ]);
        }
        exit;
    }
    
    if ($action === 'logout') {
        // Unset all session variables
        $_SESSION = [];
        // Destroy cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        // Destroy session
        session_destroy();
        
        echo json_encode([
            "status" => "success",
            "message" => "Logged out successfully."
        ]);
        exit;
    }
    
    echo json_encode(["status" => "error", "message" => "Invalid action."]);
    exit;
}

if ($method === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Username and password are required."]);
        exit;
    }

    $username = $conn->real_escape_string($username);
    $sql = "SELECT id, username, password, full_name, role FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // In local demo we support both plaintext and hashed passwords for easy testing
        if ($password === $row['password'] || password_verify($password, $row['password'])) {
            $user = [
                "id" => $row['id'],
                "username" => $row['username'],
                "full_name" => $row['full_name'],
                "role" => $row['role']
            ];
            
            $_SESSION['user'] = $user;
            
            echo json_encode([
                "status" => "success",
                "message" => "Authentication successful.",
                "user" => $user
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username not found."]);
    }

    $conn->close();
    exit;
}

echo json_encode(["status" => "error", "message" => "Method not allowed."]);
?>
