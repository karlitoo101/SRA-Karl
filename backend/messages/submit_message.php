<?php
session_start();
require_once '../dbconnection/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sender = trim($_POST['sender']);
    $receiver = trim($_POST['receiver']);
    $message = trim($_POST['message']);

    if (empty($sender) || empty($receiver) || empty($message)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid input."]);
        exit;
    }

    try {
        // Changed 'sent' to 'Unread' to match your database ENUM and notification logic
        $sql = "INSERT INTO chat_messages (sender, receiver, message, status, created_at)
            VALUES (:sender, :receiver, :message, 'Unread', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sender', $sender);
        $stmt->bindParam(':receiver', $receiver);
        $stmt->bindParam(':message', $message);
        $stmt->execute();

        echo json_encode(["status" => "success", "message" => "Message sent."]);
    } catch (PDOException $e) {
        error_log("Message insert failed: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error sending message."]);
    }
}
?>