<?php


header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "library_app");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $member_name = $_POST["member_name"] ?? null;
    $member_phone = $_POST["member_phone"] ?? null;
    $member_nim = $_POST["member_nim"] ?? null;
    $member_major = $_POST["member_major"] ?? null;

    if ($member_name && $member_phone && $member_nim && $member_major) {
        // // Generate custom member_id (e.g., M0001, M0002, etc.)
        // $result = $conn->query("SELECT MAX(member_id) AS last_id FROM member");
        // $row = $result->fetch_assoc();
        // $last_id = $row["last_id"] ?? "M0000";
        // $new_id = "M" . str_pad((int) substr($last_id, 1) + 1, 4, "0", STR_PAD_LEFT);

        $stmt = $conn->prepare("INSERT INTO member2 (member_id, member_name, member_phone, member_nim, member_major) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $new_id, $member_name, $member_phone, $member_nim, $member_major);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "member_id" => $new_id]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to insert member"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "All fields are required"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}

$conn->close();
?>
