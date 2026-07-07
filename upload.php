<?php
header('Content-Type: application/json');
require_once 'session_helper.php';

// Enforce backend authentication
require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

if (!isset($_FILES['file'])) {
    echo json_encode(["status" => "error", "message" => "No file uploaded."]);
    exit;
}

$file = $_FILES['file'];
$fileName = basename($file['name']);
$fileTmpName = $file['tmp_name'];
$fileSize = $file['size'];
$fileError = $file['error'];

if ($fileError !== 0) {
    echo json_encode(["status" => "error", "message" => "Error during file upload: " . $fileError]);
    exit;
}

// Allowed extensions for social portal uploads
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileExt, $allowed)) {
    echo json_encode(["status" => "error", "message" => "Invalid file type. Allowed formats: " . implode(', ', $allowed)]);
    exit;
}

// Max 10MB file limit
if ($fileSize > 10 * 1024 * 1024) {
    echo json_encode(["status" => "error", "message" => "File size exceeds the 10MB limit."]);
    exit;
}

// Create uploads directory if it does not exist, ensure it is writable
$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}
// Force write permissions if not writable (Linux servers)
if (!is_writable($uploadDir)) {
    chmod($uploadDir, 0775);
}
// Final check - abort if still not writable
if (!is_writable($uploadDir)) {
    echo json_encode(["status" => "error", "message" => "Upload directory is not writable. Please set chmod 775 on: " . $uploadDir]);
    exit;
}

// Sanitize filename - strip unicode/non-ASCII characters, replace spaces
$cleanFileName = preg_replace('/[^\x20-\x7E]/', '', $fileName); // strip non-ASCII
$cleanFileName = preg_replace('/\s+/', '_', $cleanFileName);      // replace spaces
$cleanFileName = preg_replace('/[^a-zA-Z0-9_\.\-]/', '', $cleanFileName); // strip remaining unsafe chars
if (empty($cleanFileName) || $cleanFileName === '.' . $fileExt) {
    $cleanFileName = 'file.' . $fileExt;
}

$newFileName = uniqid('file_', true) . '_' . $cleanFileName;
$destPath = $uploadDir . $newFileName;

if (move_uploaded_file($fileTmpName, $destPath)) {
    @chmod($destPath, 0644);
    echo json_encode([
        "status" => "success",
        "message" => "File uploaded successfully.",
        "file_url" => 'uploads/' . $newFileName
    ]);
} else {
    $phpError = error_get_last();
    echo json_encode(["status" => "error", "message" => "Failed to move uploaded file. Dir: " . $uploadDir . " | PHP: " . ($phpError['message'] ?? 'unknown')]);
}
?>
