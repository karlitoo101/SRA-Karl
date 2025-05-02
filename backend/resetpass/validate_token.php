<?php
// Include the database connection
include '../dbconnection/db.php';

// For debugging purposes
$debug = true;

// Check if token is provided
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Create a debug array to collect information
    $debugInfo = [];
    $debugInfo['raw_token'] = $token;
    
    // Hash the token for comparison with the stored hash
    $hashed_token = hash('sha256', $token);
    $debugInfo['hashed_token'] = $hashed_token;
    
    // Log debug information to a file
    if ($debug) {
        error_log("Token validation request - Raw token: " . $token);
        error_log("Token validation request - Hashed token: " . $hashed_token);
    }
    
    // Prepare SQL to check token validity
    $stmt = $conn->prepare("SELECT userID, reset_token_hash, reset_token_expires_at FROM users WHERE reset_token_hash = ?");
    
    if (!$stmt) {
        if ($debug) {
            error_log("Database error: " . $conn->error);
            header('Content-Type: application/json');
            echo json_encode(['valid' => false, 'message' => 'Database error: ' . $conn->error, 'debug' => $debugInfo]);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['valid' => false, 'message' => 'Database error. Please try again.']);
            exit;
        }
    }
    
    $stmt->bind_param("s", $hashed_token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Get the number of rows returned
    $rowCount = $result->num_rows;
    $debugInfo['rows_found'] = $rowCount;
    
    if ($debug) {
        error_log("Token validation - Found " . $rowCount . " matching rows");
    }

    if ($row = $result->fetch_assoc()) {
        $expires = $row['reset_token_expires_at'];
        $current_time = date("Y-m-d H:i:s");
        
        $debugInfo['user_id'] = $row['userID'];
        $debugInfo['expires'] = $expires;
        $debugInfo['current_time'] = $current_time;
        
        if ($debug) {
            error_log("Token validation - User ID: " . $row['userID']);
            error_log("Token validation - Current time: " . $current_time . ", Expiry: " . $expires);
        }
        
        // Check if the token has expired
        if ($current_time < $expires) {
            // Token is valid
            header('Content-Type: application/json');
            if ($debug) {
                echo json_encode(['valid' => true, 'debug' => $debugInfo]);
            } else {
                echo json_encode(['valid' => true]);
            }
        } else {
            if ($debug) {
                error_log("Token expired - Current: $current_time, Expires: $expires");
                header('Content-Type: application/json');
                echo json_encode(['valid' => false, 'message' => 'Token has expired. Please request a new reset link.', 'debug' => $debugInfo]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['valid' => false, 'message' => 'Token has expired. Please request a new reset link.']);
            }
        }
    } else {
        if ($debug) {
            error_log("No matching token found in database");
            header('Content-Type: application/json');
            echo json_encode(['valid' => false, 'message' => 'Invalid token.', 'debug' => $debugInfo]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['valid' => false, 'message' => 'Invalid token.']);
        }
    }
    
    $stmt->close();
} else {
    header('Content-Type: application/json');
    echo json_encode(['valid' => false, 'message' => 'No token provided.']);
}
?>