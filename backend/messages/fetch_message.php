<?php
session_start();
require_once '../dbconnection/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $markAsRead = isset($_POST['markAsRead']) ? $_POST['markAsRead'] : false;

    // Fetch all messages between sender and receiver
    $sql = "SELECT * FROM chat_messages 
            WHERE (sender=? AND receiver=?) OR (sender=? AND receiver=?) 
            ORDER BY created_at";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
    $stmt->execute();
    $result = $stmt->get_result();

    // Mark messages as read if the flag is true (i.e., if a user is viewing the message)
    if ($markAsRead == "true") {
        $updateSql = "UPDATE chat_messages 
                      SET status = 'Read' 
                      WHERE sender = ? AND receiver = ? AND status = 'Unread'";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $receiver, $sender);  // For admin: receiver is admin, sender is trader
        $updateStmt->execute();
        $updateStmt->close();
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $msgSender = htmlspecialchars(ucfirst($row['sender']));
            $message = htmlspecialchars($row['message']);
            $timestamp = date("H:i", strtotime($row['created_at']));
            $status = isset($row['status']) ? htmlspecialchars($row['status']) : '';

            // Check if it's the current user's message
            $isOwnMessage = ($row['sender'] === $_POST['sender']);

            echo '<div class="message-container ' . ($isOwnMessage ? 'own' : 'other') . '">';
            echo '<div class="sender-name">' . $msgSender . '</div>';
            echo '<div class="message">' . $message . '</div>';
            echo '<div class="meta">';
            echo '<span class="timestamp">' . $timestamp . '</span>';
            if ($isOwnMessage) {
                echo '<span class="status">' . $status . '</span>';
            }
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="message no-msg">No messages yet.</div>';
    }

    $stmt->close();
}
?>
