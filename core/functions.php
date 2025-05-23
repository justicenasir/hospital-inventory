<?php
// CSRF token helpers
function generate_csrf_token() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function check_csrf_token($token) {
    return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}

// Simple input sanitizer
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Redirect with message
function redirect_with_message($url, $message) {
    $_SESSION['flash_message'] = $message;
    header("Location: $url");
    exit();
}

// Display flash message
function display_flash_message() {
    if (!empty($_SESSION['flash_message'])) {
        echo '<div class="alert alert-info">' . sanitize($_SESSION['flash_message']) . '</div>';
        unset($_SESSION['flash_message']);
    }
}
?>