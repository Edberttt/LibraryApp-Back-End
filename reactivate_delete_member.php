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
if (isset($_POST['member_id'])) {
    $member_id = $_POST['member_id'];

    // Prepare the SQL query for a soft delete
    $sql = "UPDATE member2 SET delete_status = false WHERE member_id = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $member_id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Member reactivate successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to reactivate the member: " . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Member ID is required."
    ]);
}

// Close database connection
$conn->close();
?>