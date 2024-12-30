<?php
// Set headers for the response
header("Content-Type: application/json");

// Get the input data (in JSON format)
$data = json_decode(file_get_contents("php://input"), true);

// Check if required data is provided
if (!isset($data['loan_id']) || !isset($data['book_id']) || !isset($data['member_id']) || !isset($data['loan_date']) || !isset($data['return_date'])) {
    echo json_encode(["success" => false, "message" => "Missing required parameters"]);
    exit();
}

// Extract data from the request
$loan_id = $data['loan_id'];
$book_id = $data['book_id'];
$member_id = $data['member_id'];
$loan_date = $data['loan_date'];
$return_date = $data['return_date'];

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "library_app"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Prepare the SQL statement to update the loan
$sql = "UPDATE Loan2 SET book_id = ?, member_id = ?, loan_date = ?, return_date = ? WHERE loan_id = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $book_id, $member_id, $loan_date, $return_date, $loan_id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Loan updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating loan: " . $stmt->error]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
