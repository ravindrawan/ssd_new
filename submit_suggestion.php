<?php
header('Content-Type: application/json');
require_once 'db_config.php';

if (isset($db_connection_error) && $db_connection_error !== null) {
    echo json_encode(["status" => "error", "message" => "Database Connection Error: " . $db_connection_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$subject = isset($_POST['subject']) && !empty(trim($_POST['subject'])) ? trim($_POST['subject']) : 'Feedback';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields (Name, Email, Message)."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address format."]);
    exit;
}

// Store raw values for the email before escaping for the database
$email_name = $name;
$email_user = $email;
$email_msg = $message;
$email_sub = $subject;

$name = $conn->real_escape_string($name);
$email = $conn->real_escape_string($email);
$phone = $conn->real_escape_string($phone);
$subject = $conn->real_escape_string($subject);
$message = $conn->real_escape_string($message);

$sql = "INSERT INTO suggestions (name, email, phone, subject, message) VALUES ('$name', '$email', '$phone', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
    // Send email notification to socidepnwp@gmail.com
    $to = "socidepnwp@gmail.com";
    $email_subject = "New Feedback/Suggestion from Web Portal: " . $email_sub;
    
    // HTML Email body formatting
    $email_body = "
    <html>
    <head>
        <title>New Suggestion/Feedback</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333333; }
            .container { padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; max-width: 600px; background-color: #f8fafc; }
            .header { background-color: #991b1b; color: #ffffff; padding: 15px; border-top-left-radius: 8px; border-top-right-radius: 8px; margin: -20px -20px 20px -20px; }
            .header h2 { margin: 0; font-size: 1.3rem; }
            .field-row { margin-bottom: 12px; }
            .field-label { font-weight: bold; color: #475569; font-size: 0.9rem; margin-bottom: 4px; }
            .field-value { padding: 10px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; white-space: pre-wrap; font-size: 0.95rem; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Suggestion & Feedback Submission</h2>
            </div>
            <div class='field-row'>
                <div class='field-label'>Sender's Full Name:</div>
                <div class='field-value'>" . htmlspecialchars($email_name) . "</div>
            </div>
            <div class='field-row'>
                <div class='field-label'>Sender's Email Address:</div>
                <div class='field-value'><a href='mailto:" . htmlspecialchars($email_user) . "'>" . htmlspecialchars($email_user) . "</a></div>
            </div>
            <div class='field-row'>
                <div class='field-label'>Message / Suggestion:</div>
                <div class='field-value'>" . nl2br(htmlspecialchars($email_msg)) . "</div>
            </div>
            <div style='margin-top: 25px; font-size: 0.75rem; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;'>
                This email was generated automatically by the Wayamba Province Social Services Web Portal.
            </div>
        </div>
    </body>
    </html>
    ";

    // Get the current domain dynamically to bypass hosting spoofing filters
    $domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'socialservicesnwp.lk';
    $domain = explode(':', $domain)[0];
    if (substr($domain, 0, 4) == 'www.') {
        $domain = substr($domain, 4);
    }
    if ($domain == 'localhost' || $domain == '127.0.0.1') {
        $domain = 'socialservicesnwp.lk';
    }
    $from_email = "noreply@" . $domain;

    // Set MIME headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Wayamba Social Services Web Portal <" . $from_email . ">" . "\r\n";
    $headers .= "Reply-To: " . $email_name . " <" . $email_user . ">" . "\r\n";

    // Skip email sending in local environment to prevent synchronous network timeouts
    $is_localhost = ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1');
    if (!$is_localhost) {
        // Send email using PHP mail() with envelope sender parameter (-f)
        @mail($to, $email_subject, $email_body, $headers, "-f " . $from_email);
    }

    echo json_encode(["status" => "success", "message" => "Suggestion submitted successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save suggestion: " . $conn->error]);
}

$conn->close();
?>
