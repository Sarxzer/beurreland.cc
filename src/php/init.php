<?php
// Initialization file for the application, including database connection and utility functions

/**
 * Root path
 */
define('BASE_PATH', __DIR__ . '/../../'); // Define the base path for the application

require_once BASE_PATH . 'vendor/autoload.php'; // Include Composer autoload for environment variables

// Load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

require_once BASE_PATH . 'src/php/database.php'; // Include the database connection
require_once BASE_PATH . 'src/php/utils.php'; // Include utility functions

// Set the default timezone
date_default_timezone_set('Europe/Paris'); // Set the default timezone to Europe/Paris (Timezone for France)

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// show errors in development mode
if ($_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}