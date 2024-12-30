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
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Fetch books
$sql = "SELECT * FROM books2";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit();
}

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    echo json_encode($books, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["message" => "No books found"]);
}

$conn->close();
?>
