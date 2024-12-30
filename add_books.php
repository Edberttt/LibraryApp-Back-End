<?php
// Connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get POST data
$book_name = isset($_POST['book_name']) ? mysqli_real_escape_string($conn, $_POST['book_name']) : '';
$author_name = isset($_POST['author_name']) ? mysqli_real_escape_string($conn, $_POST['author_name']) : '';
$book_year = isset($_POST['book_year']) ? mysqli_real_escape_string($conn, $_POST['book_year']) : '';

// Validate the input
if (!empty($book_name) && !empty($author_name) && !empty($book_year)) {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO Books2 (book_name, author_name, book_year) VALUES (?, ?, ?)");
    
    // Check if statement preparation succeeded
    if ($stmt === false) {
        echo json_encode(["error" => "Failed to prepare the SQL statement"]);
        exit();
    }
    
    // Bind the parameters to the SQL query
    $stmt->bind_param("sss", $book_name, $author_name, $book_year);
    
    // Execute query
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to insert data: " . $stmt->error]);
    }
    
    // Close the statement
    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid input"]);
}

// Close connection
$conn->close();
?>
