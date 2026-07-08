<?php
// Database Configuration for Social Services NWP
mysqli_report(MYSQLI_REPORT_OFF);


$db_host = 'engdept-db';
$db_user = 'eng_user'; 
$db_name = 'social_services_nwp_db';

// Try multiple password options to make it work in different developer environments
$passwords_to_try = ['eng_pass_2026', '', 'eng_user'];
$conn = null;
$db_connection_error = null;

foreach ($passwords_to_try as $test_pass) {
    $conn = @new mysqli($db_host, $db_user, $test_pass, $db_name);
    if (!$conn->connect_error) {
        $db_pass = $test_pass;
        $db_connection_error = null;
        break;
    } else {
        $db_connection_error = $conn->connect_error;
    }
}

if ($conn && !$conn->connect_error) {
    if (!$conn->set_charset("utf8mb4")) {
        $conn->set_charset("utf8");
    }
}

// Log connection attempt from Apache/Web or CLI
$sapi = PHP_SAPI;
$status_msg = ($db_connection_error === null) ? "SUCCESS" : "FAIL: " . $db_connection_error;
$log_line = "[" . date('Y-m-d H:i:s') . "] SAPI: $sapi | host: $db_host | status: $status_msg\n";
@file_put_contents(__DIR__ . '/db_log.txt', $log_line, FILE_APPEND);
