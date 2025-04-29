<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';



include '../dbconnection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Find user
    $stmt = $conn->prepare("SELECT userID FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $userID = $row['userID'];
        $token = bin2hex(random_bytes(32)); // raw token
        $tokenHash = hash('sha256', $token); // store hash
        $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour

        // Store hashed token
        $stmt = $conn->prepare("UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE userID = ?");
        $stmt->bind_param("ssi", $tokenHash, $expires, $userID);
        $stmt->execute();

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your@email.com';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your@email.com', 'Your App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';

            $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
            $mail->Body = "Click to reset: <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            echo "Check your email for a reset link.";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email not found.";
    }
}
?>
