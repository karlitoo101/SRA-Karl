<?php
// Include the database connection file
include('../backend/dbconnection/db.php'); // Ensure this path is correct

// ======== GET AND SANITIZE POST DATA ========
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$raw_password = $_POST['password'] ?? '';

// ======== VALIDATION ========
if (empty($name) || empty($email) || empty($raw_password)) {
    echo "Please fill in all required fields.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
}

// ======== HASH PASSWORD USING BCRYPT ========
$hashed_password = password_hash($raw_password, PASSWORD_BCRYPT);

try {
    // ======== CHECK IF EMAIL ALREADY EXISTS ========
    $check_sql = "SELECT userID FROM users WHERE email = :email";
    $stmt = $pdo->prepare($check_sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "This email is already registered.";
    } else {
        // ======== INSERT ADMIN ACCOUNT ========
        $insert_sql = "INSERT INTO users (name, email, password, is_admin) 
                       VALUES (:name, :email, :password, 1)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $insert_stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $insert_stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        if ($insert_stmt->execute()) {
            echo "Admin account created successfully!";
        } else {
            echo "Error: " . $insert_stmt->errorInfo()[2];
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
