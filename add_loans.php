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

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['loan_id']) || !isset($data['book_id']) || !isset($data['member_id']) || !isset($data['loan_date'])) {
    echo json_encode(["error" => "Invalid input data"]);
    exit();
}

$loan_id = $data['loan_id'];
$book_id = $data['book_id'];
$member_id = $data['member_id'];
$loan_date = $data['loan_date'];
$return_date = isset($data['return_date']) ? $data['return_date'] : null;

// Prepare and bind
// $stmt = $conn->prepare("INSERT INTO loan2 (loan_id, book_id, member_id, loan_date, return_date) VALUES (?, ?, ?, ?, ?)");
// $stmt->bind_param("sssss", $loan_id, $book_id, $member_id, $loan_date, $return_date);
$stmt = $conn->prepare("INSERT INTO loan2 (loan_id, book_id, member_id, loan_date, return_date) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $loan_id, $book_id, $member_id, $loan_date, $return_date); // 'i' for integer loan_id


if ($stmt->execute()) {
    echo json_encode(["success" => true, "loan_id" => $loan_id]);
} else {
    echo json_encode(["error" => "Failed to add loan: " . $stmt->error]);
}

$stmt->close();
$conn->close();





?>
