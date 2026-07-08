<?php
// Database Configuration for Social Services NWP
mysqli_report(MYSQLI_REPORT_OFF);

// අලුත් MariaDB සර්විස් එකේ විස්තර
$db_host = 'mariadb'; 
$db_user = 'root'; // Root එකෙන්ම කනෙක්ට් වෙමු
$db_pass = 'QlsJaXg3PyqJ7un4'; // ඔයාගේ MYSQL_ROOT_PASSWORD එක
$db_name = 'social_services_nwp_db';

// කෙලින්ම ඩේටාබේස් එකට කනෙක්ට් වීම
$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn && !$conn->connect_error) {
    if (!$conn->set_charset("utf8mb4")) {
        $conn->set_charset("utf8");
    }
    $db_connection_error = null;
} else {
    $db_connection_error = $conn->connect_error;
}

// Log connection attempt from Apache/Web or CLI
$sapi = PHP_SAPI;
$status_msg = ($db_connection_error === null) ? "SUCCESS" : "FAIL: " . $db_connection_error;
$log_line = "[" . date('Y-m-d H:i:s') . "] SAPI: $sapi | host: $db_host | status: $status_msg\n";
@file_put_contents(__DIR__ . '/db_log.txt', $log_line, FILE_APPEND);
