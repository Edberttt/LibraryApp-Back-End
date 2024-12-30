<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_app";

// Connect to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if book_id is provided in the request
if (isset($_POST['loan_id'])) {
    $loan_id = $_POST['loan_id'];

    // Prepare the SQL query for a soft delete
    $sql = "UPDATE loan2 SET delete_status = false WHERE loan_id = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $loan_id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Loan soft deleted successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to soft delete the Loan: " . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Loan ID is required."
    ]);
}

// Close database connection
$conn->close();
?>