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
$member_id = isset($_POST['member_id']) ? intval($_POST['member_id']) : null;
$member_name = isset($_POST['member_name']) ? mysqli_real_escape_string($conn, $_POST['member_name']) : '';
$member_phone = isset($_POST['member_phone']) ? mysqli_real_escape_string($conn, $_POST['member_phone']) : '';
$member_nim = isset($_POST['member_nim']) ? mysqli_real_escape_string($conn, $_POST['member_nim']) : '';
$member_major = isset($_POST['member_major']) ? mysqli_real_escape_string($conn, $_POST['member_major']) : '';

// Validate input
if (!$member_id || empty($member_name) || empty($member_phone) || empty($member_nim) || empty($member_major)) {
    echo json_encode(["error" => "Invalid input: All fields are required"]);
    exit();
}

// Prepare and bind
$stmt = $conn->prepare("UPDATE member2 SET member_name = ?, member_phone = ?, member_nim = ?, member_major = ? WHERE member_id = ?");
if ($stmt === false) {
    echo json_encode(["error" => "Failed to prepare the SQL statement"]);
    exit();
}

$stmt->bind_param("ssssi", $member_name, $member_phone, $member_nim, $member_major, $member_id);

// Execute query
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Member updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or invalid Member ID"]);
    }
} else {
    echo json_encode(["error" => "Failed to update data: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
