<?php
header('Content-Type: application/json');
require_once '../dbconnection/db.php'; // ✅ your PDO connection file

try {
    // Get POST data
    $companyName = $_POST['companyName'];
    $email = $_POST['emailAddress'];
    $contactNumber = $_POST['traderContactNumber'];
    $telephoneNumber = $_POST['telephoneNumber'];
    $address = $_POST['companyAddress'];
    $region = $_POST['region'];
    $rawPassword = $_POST['password'];

    // ✅ Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(['success' => false, 'error' => 'Email already exists.']);
        exit;
    }

    // ✅ Insert into `users` table
    $hashedPassword = password_hash($rawPassword, PASSWORD_BCRYPT);
    $role = 0; // 0 = normal user, 1 = admin

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
    $stmt->execute([$companyName, $email, $hashedPassword, $role]);

    $userID = $pdo->lastInsertId();

    // ✅ Insert into `traders` table
    $stmt = $pdo->prepare("INSERT INTO traders (userID, traderName, contactNumber, telephoneNumber, address, region) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userID, $companyName, $contactNumber, $telephoneNumber, $address, $region]);

    echo json_encode(['success' => true, 'message' => 'Trader account created successfully!']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
