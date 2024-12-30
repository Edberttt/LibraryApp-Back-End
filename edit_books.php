<?php
// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Enable error reporting (for debugging purposes, remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get POST data
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : null;
$book_name = isset($_POST['book_name']) ? mysqli_real_escape_string($conn, $_POST['book_name']) : '';
$author_name = isset($_POST['author_name']) ? mysqli_real_escape_string($conn, $_POST['author_name']) : '';
$book_year = isset($_POST['book_year']) ? mysqli_real_escape_string($conn, $_POST['book_year']) : '';

// Validate input
if (!$book_id || empty($book_name) || empty($author_name) || empty($book_year)) {
    echo json_encode(["error" => "Invalid input: All fields are required"]);
    exit();
}

// Prepare and bind
$stmt = $conn->prepare("UPDATE books2 SET book_name = ?, author_name = ?, book_year = ? WHERE book_id = ?");
if ($stmt === false) {
    echo json_encode(["error" => "Failed to prepare the SQL statement"]);
    exit();
}

$stmt->bind_param("sssi", $book_name, $author_name, $book_year, $book_id);

// Execute query
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Book updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or invalid book ID"]);
    }
} else {
    echo json_encode(["error" => "Failed to update data: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
