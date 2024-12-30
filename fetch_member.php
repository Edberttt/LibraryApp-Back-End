<?php

// Enable CORS (if needed)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Enable error reporting (for debugging purposes, remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root"; // Default username
$password = ""; // Default password
$dbname = "Library_App";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode([]);
    exit();
}

// Fetch members
$sql = "SELECT * FROM member2";
$result = $conn->query($sql);

$members = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    echo json_encode($members, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([]);
}

$conn->close();

?>
