<?php
header('Content-Type: application/json');
include '../dbconnection/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? '';
$newPassword = $data['newPassword'] ?? '';

if (!$token || !$newPassword) {
    echo json_encode(["success" => false, "message" => "Missing data."]);
    exit;
}

$tokenHash = hash('sha256', $token);

// Check if token is valid
$stmt = $conn->prepare("SELECT userID FROM users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()");
$stmt->bind_param("s", $tokenHash);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $userID = $row['userID'];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password and clear reset token
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE userID = ?");
    $stmt->bind_param("si", $hashedPassword, $userID);
    $stmt->execute();

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid or expired token."]);
}
?>
