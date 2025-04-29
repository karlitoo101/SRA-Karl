<?php
session_start();
include('../backend/dbconnection/db.php'); // database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Login user by email
        $sql = "SELECT userID, name, email, password, is_admin FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                // Store logged-in user info
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];

                // Now fetch user with userID = 1 (e.g., admin, support bot, etc.)
                $receiverID = 1;
                $adminStmt = $pdo->prepare("SELECT userID, name FROM users WHERE userID = :id");
                $adminStmt->bindParam(':id', $receiverID, PDO::PARAM_INT);
                $adminStmt->execute();

                if ($adminStmt->rowCount() === 1) {
                    $receiver = $adminStmt->fetch(PDO::FETCH_ASSOC);
                    // Store receiver info in session
                    $_SESSION['receiverID'] = $receiver['userID'];
                    $_SESSION['receiverName'] = $receiver['name'];
                } else {
                    // Default fallback in case userID 1 doesn't exist
                    $_SESSION['receiverID'] = null;
                    $_SESSION['receiverName'] = 'Unknown';
                }

                // Return role
                echo $user['is_admin'] ? "admin" : "user";
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "Email not found.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>