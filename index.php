<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INDEX</title>
</head>
<body>
</body>
</html>
<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['userID'])) {
    // Redirect to dashboard based on role
    if ($_SESSION['role'] === 'admin') {
        header('Location: frontend/admin/traderlist.php');
    } else {
        header('Location: frontend/trader/traderdashboard.html');
    }
    exit;
} else {
    // If not logged in, go to login page
    header('Location: logins/login.html ');
    exit;
}
?>