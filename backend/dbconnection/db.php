<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sradb2";

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
