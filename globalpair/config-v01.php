<?php
// Database configuration for GlobePair Dating App

// Database constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'globepair_db');

echo "Database configuration initialized successfully.";

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialization success
echo "Connected successfully to the database.";
?>