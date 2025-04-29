<?php
session_start();
require_once '../dbconnection/db.php'; // assumes $pdo is defined in this file

$userID = $_SESSION['userID'];

// Get POST data
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo "All fields are required.";
    exit();
}

if ($newPassword !== $confirmPassword) {
    echo "Passwords do not match.";
    exit();
}

// Fetch current hashed password from DB
$stmt = $pdo->prepare("SELECT password FROM users WHERE userID = :userID");
$stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($currentPassword, $user['password'])) {
    echo "Current password is incorrect.";
    exit();
}

// Hash new password using bcrypt
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Update in DB
$update = $pdo->prepare("UPDATE users SET password = :password WHERE userID = :userID");
$update->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
$update->bindParam(':userID', $userID, PDO::PARAM_INT);

if ($update->execute()) {
    echo "success";
} else {
    echo "Failed to update password.";
}