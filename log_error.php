<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$error = isset($_POST['error']) ? $_POST['error'] : 'Unknown error';
$url = isset($_POST['url']) ? $_POST['url'] : '';
$line = isset($_POST['line']) ? $_POST['line'] : '';
$col = isset($_POST['col']) ? $_POST['col'] : '';

$log_entry = "[" . date("Y-m-d H:i:s") . "] Error: $error | URL: $url | Line: $line | Col: $col\n";
file_put_contents("js_errors.log", $log_entry, FILE_APPEND);

echo json_encode(["status" => "logged"]);
?>
