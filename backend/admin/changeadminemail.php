<?php
session_start();
require_once '../dbconnection/db.php'; // Adjust path if needed

// Get the raw POST data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad Request
    echo "Invalid email.";
    exit();
}
$userID = $_SESSION['userID'];
try {
    $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE userID = :userID");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Email updated successfully!";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Failed to update email.";
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}
