<?php
// Session settings (move these before any session_start!)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'hospital_store');
define('DB_USER', 'root');
define('DB_PASS', '');

// CSRF token constant
define('CSRF_TOKEN_NAME', '_csrf_token');