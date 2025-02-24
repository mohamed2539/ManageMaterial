<?php
// Include the autoloader
require_once 'app/core/autoloader.php';

// Test loading the Database class
try {
    $db = \config\Database::connect(); // Updated to lowercase
    echo "Database connection successful!";
} catch (\Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}