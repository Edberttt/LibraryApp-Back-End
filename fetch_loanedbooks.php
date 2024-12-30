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

// Fetch loans with book_name and member_name
$sql = "
    SELECT loan2.loan_id, loan2.book_id, loan2.member_id, loan2.loan_date, loan2.return_date, 
           books2.book_name, member2.member_name
    FROM loan2
    JOIN books2 ON loan2.book_id = books2.book_id
    JOIN member2 ON loan2.member_id = member2.member_id
";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit();
}

$loans = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loans[] = $row;
    }
    echo json_encode($loans, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["message" => "No loans found"]);
}

$conn->close();

?>
