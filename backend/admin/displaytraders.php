<?php
// getTraders.php
require_once '../dbconnection/db.php';

// Fetch all traders from the database
$sql = "SELECT traderName, contactNumber, telephoneNumber, region FROM traders";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$traders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return traders data as JSON
echo json_encode($traders);
?>
