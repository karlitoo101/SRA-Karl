<?php
session_start();
require_once '../dbconnection/db.php';

$adminName = $_SESSION['name'];

// Note the UPPER case 'Unread' to match your database enum
$sql = "SELECT sender, COUNT(*) AS unread_count FROM chat_messages 
        WHERE receiver = ? AND status = 'Unread'
        GROUP BY sender";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminName);
$stmt->execute();
$result = $stmt->get_result();

$unread = [];
while ($row = $result->fetch_assoc()) {
    $unread[$row['sender']] = (int) $row['unread_count'];
}

echo json_encode($unread);
?>