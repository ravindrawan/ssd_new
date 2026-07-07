<?php
// Session security helper for Wayamba Social Services Department CMS
if (session_status() === PHP_SESSION_NONE) {
    session_name('SSDWEBSESSID');
    
    // Set secure cookie parameters if possible (good practice)
    $cookieParams = session_get_cookie_params();
    $isSecure = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || 
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
    
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true, // Prevent JavaScript access to session cookie
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Check if the user is currently authenticated
 */
function is_authenticated() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

/**
 * Enforce authentication. Returns JSON error and terminates execution if not logged in.
 */
function require_auth() {
    if (!is_authenticated()) {
        echo json_encode([
            "status" => "error",
            "message" => "Unauthorized. Please log in to perform this operation."
        ]);
        exit;
    }
}

/**
 * Enforce authentication for modifying requests (POST, DELETE).
 * Permits GET requests without login to support public frontend fetches.
 */
function require_auth_for_write() {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method !== 'GET') {
        require_auth();
    }
}
