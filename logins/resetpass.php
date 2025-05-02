<?php
// Include the database connection
include '../backend/dbconnection/db.php';

// Initialize variables
$tokenValid = false;
$errorMessage = '';

// Validate token if provided
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // For debugging purposes
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // Build the validation URL
    $validationUrl = "http://localhost/SRA-Karl/backend/resetpass/validate_token.php?token=" . urlencode($token);
    
    // Log the URL we're calling
    error_log("Validating token with URL: " . $validationUrl);
    
    // Set up cURL request to validate token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $validationUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Make sure we get all errors
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Add this for local testing
    
    // Execute cURL request
    $validationResponse = curl_exec($ch);
    
    // Check for cURL errors
    if (curl_errno($ch)) {
        $errorMessage = "Server communication error: " . curl_error($ch);
        error_log("cURL error in password reset: " . curl_error($ch));
    } else {
        // Get HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        error_log("HTTP Status Code: " . $httpCode);
        
        // Log the raw response for debugging
        error_log("Raw validation response: " . $validationResponse);
        
        // Parse JSON response
        $validationData = json_decode($validationResponse, true);
        
        // Check if JSON parsing was successful
        if ($validationData === null) {
            error_log("Invalid JSON response: " . $validationResponse);
            $errorMessage = "There was an error processing the server response. Please try again.";
        } else {
            // Log the parsed data
            error_log("Parsed validation data: " . print_r($validationData, true));
            
            // Check if token is valid
            if (isset($validationData['valid']) && $validationData['valid']) {
                $tokenValid = true;
                error_log("Token validation successful");
            } else {
                // Get the error message or provide a default
                $errorMessage = isset($validationData['message']) ? $validationData['message'] : "Invalid token.";
                error_log("Token validation failed: " . $errorMessage);
                
                // If debug info is available, log it
                if (isset($validationData['debug'])) {
                    error_log("Debug info: " . print_r($validationData['debug'], true));
                }
            }
        }
    }
    
    // Close cURL connection
    curl_close($ch);
} else {
    $errorMessage = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugar Regulatory Administration - Set New Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/resetpass.css">
</head>

<body>
    <div class="logo">
        <img src="../resources/SRA_thumbnail.png" alt="Department of Agriculture Logo">
    </div>

    <div class="content-area">
        <?php if ($tokenValid): ?>
            <h1>Set new password</h1>
            <p class="instruction">Create a new password. Ensure it differs from previous ones for security.</p>

            <form id="resetPasswordForm">
                <!-- Hidden token field -->
                <input type="hidden" id="token" value="<?php echo htmlspecialchars($token); ?>" />

                <div class="form-group">
                    <label for="newPassword">Password</label>
                    <div class="password-input-container">
                        <input type="password" id="newPassword" class="password-input"
                            placeholder="Enter your new password">
                        <i class="toggle-password fas fa-eye-slash" onclick="togglePasswordVisibility('newPassword')"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="password-input-container">
                        <input type="password" id="confirmPassword" class="password-input" placeholder="Re-enter password">
                        <i class="toggle-password fas fa-eye-slash"
                            onclick="togglePasswordVisibility('confirmPassword')"></i>
                    </div>
                </div>

                <button type="submit" class="update-btn">Update Password</button>
            </form>

        <?php else: ?>
            <h1>Error</h1>
            <p class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </p>
        <?php endif; ?>
    </div>

    <script>
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const icon = passwordInput.nextElementSibling;

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            }
        }

        document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
            event.preventDefault();
            updatePassword();
        });

        async function updatePassword() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const token = document.getElementById('token').value;
            
            // Validate password
            if (!newPassword) {
                alert("Please enter a password.");
                return;
            }
            
            // Check password match
            if (newPassword !== confirmPassword) {
                alert("Passwords do not match!");
                return;
            }

            try {
                const response = await fetch('../backend/resetpass/update_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ newPassword, token })
                });

                const result = await response.json();

                if (result.success) {
                    alert('Password updated successfully!');
                    // Redirect to login page
                    window.location.href = '../index.php';
                } else {
                    alert(result.message || 'Failed to update password.');
                }
            } catch (error) {
                console.error('Error updating password:', error);
                alert('An error occurred while updating the password. Please try again.');
            }
        }
    </script>
</body>

</html>