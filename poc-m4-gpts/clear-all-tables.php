<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "m4_demo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Disable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS=0");

// Get list of all tables
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $table = $row[0];
    // Truncate each table
    $conn->query("TRUNCATE TABLE $table");
}

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS=1");

echo "All tables have been truncated.";

$conn->close();