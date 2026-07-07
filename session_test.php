<?php
header('Content-Type: application/json');
require_once 'db_config.php';
require_once 'session_helper.php';

$users = [];
if ($conn && !$conn->connect_error) {
    $res = $conn->query("SELECT id, username, full_name, role FROM users");
    if ($res) {
        while($row = $res->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        $users = "Error querying users table: " . $conn->error;
    }
}

$response = [
    "session_status" => session_status(),
    "session_id" => session_id(),
    "is_authenticated" => is_authenticated(),
    "session_user" => isset($_SESSION['user']) ? $_SESSION['user'] : null,
    "cookies" => $_COOKIE,
    "server_https" => isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'not set',
    "server_port" => isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 'not set',
    "http_x_forwarded_proto" => isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : 'not set',
    "db_connected" => ($conn && !$conn->connect_error) ? "yes" : "no",
    "db_error" => $db_connection_error,
    "db_users" => $users
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
