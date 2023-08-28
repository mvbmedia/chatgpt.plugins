<?php
# website settings
DEFINE('DOMAIN', 'plugin.support');
DEFINE('WEBSITE', 179);

# ini settings
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);

# session start
session_set_cookie_params([
    'lifetime' => 31536000,
    'path' => '/',
    'domain' => '.' . DOMAIN,
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None',
]);
session_start();

# error reporting
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
ini_set('default_socket_timeout', 5);

# headers
header('Cache-Control: public, max-age=63072000');
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60 * 24 * 90)));
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

# Content Security Policy (CSP) header with nonce
$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' 'nonce-$nonce' https://apis.google.com https://www.googletagmanager.com https://www.google-analytics.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; connect-src 'self' https://region1.google-analytics.com");

# timezone setting
date_default_timezone_set('Europe/Amsterdam');

# database credentials
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_USER', 'db_name_prive');
DEFINE('DB_PASS', 'db_password_prive');
DEFINE('DB_NAME', 'db_name_prive');

# variables
DEFINE('ROOT', dirname(dirname(__DIR__)) . '/');
DEFINE('APP', ROOT . 'application/');
DEFINE('CONTROLLER', ROOT . 'application/controllers/');
DEFINE('MODEL', ROOT . 'application/models/');
DEFINE('VIEW', ROOT . 'application/views/');
DEFINE('FRAMEWORK', ROOT . 'framework/');
DEFINE('CORE', ROOT . 'framework/core/');