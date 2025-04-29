<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 0) {
    // Redirect to login page if not an admin
    header("Location: ../../logins/logout.php"); // Change the path as needed
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drafts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f5f5f5;
            transition: filter 0.3s ease;
        }
        
        body.blur-background {
            filter: blur(5px);
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            background-color: white;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        }
        
        .logo {
            width: 70px;    
            height: 70px;
            margin-left: 25px;
        }

        .secondLogo {
            width: 60px;
            height: 60px;
            margin-left: -995px;
        }
        
        nav {                               
            display: flex;
            gap: 40px;
            align-items: center;
            margin-right: 20px;
        }
        
        nav a {
            text-decoration: none;
            color: #000;
            font-weight: normal;
        }
        
        nav a.active {
            font-weight: bold;
            text-decoration: none; 
            position: relative;
        }

        nav a.active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px; 
            width: 100%;
            height: 2px; 
            background-color: black; 
        }
        
        .sign-out-btn {
            font-size: 15px;
            padding: 8px 16px;
            background-color: white;
            border: 1px solid #b4b4b4;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease; 
        }

        .sign-out-btn:hover {
            background-color: #f5f5f5; 
            border-color: #333; 
        }

        /* Main Content Styles */
        .main-content {
            padding: 20px;
            flex-grow: 1;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .tabs {
            display: flex;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: inline-flex;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background: white;
            border: none;
            font-size: 14px;
        }

        .tab.active {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .reports-container {
            width: 100%;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .reports-table th {
            color: white;
            text-align: left;
            padding: 12px 15px;
            font-weight: bold;
        }

        .saved-drafts-table th {
            background-color: #008000; /* Green header */
        }

        .submitted-reports-table th {
            background-color: #f9e000; /* Yellow header */
            color: black;
        }

        .reports-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .reports-table tr:last-child td {
            border-bottom: none;
        }

        .reports-table .placeholder-row td {
            color: #888;
            font-style: italic;
            text-align: center;
            padding: 40px 15px;
            background-color: #f9f9f9;
        }

        /* Updated action-icons style */
        .action-icons {
            display: flex;
            gap: 20px;
            justify-content: center; /* Center the icons */
        }

        .action-icons button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        /* Make icons bigger */
        .action-icons i {
            font-size: 18px; /* Increase icon size */
        }

        .actions-header {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <img src="../../resources/SRA_thumbnail.png" alt="SRA Logo" class="logo">
        <img src="../../resources/REPUBLICOFPH.png" alt="SRA Logo" class="logo secondLogo">
        <nav>
            <a href="traderdashboard.php">HOME</a>
            <a href="#" class="active">DRAFTS</a>
            <button class="sign-out-btn">Sign out</button>
        </nav>
    </header>
    
    <div class="main-content">
        <div class="tabs">
            <button class="tab active" data-tab="saved-drafts">Saved Drafts</button>
            <button class="tab" data-tab="submitted-reports">Submitted Reports</button>
        </div>
        
        <div class="reports-container">
            <!-- Saved Drafts Tab Content -->
            <div class="tab-content active" id="saved-drafts">
                <table class="reports-table saved-drafts-table">
                    <thead>
                        <tr>
                            <th>REPORT NAME</th>
                            <th>DATE CREATED</th>
                            <th class="actions-header">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="placeholder-row">
                            <td colspan="3">No saved drafts available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Submitted Reports Tab Content -->
            <div class="tab-content" id="submitted-reports">
                <table class="reports-table submitted-reports-table">
                    <thead>
                        <tr>
                            <th>REPORT NAME</th>
                            <th>SUBMITTED DATE</th>
                            <th class="actions-header">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="placeholder-row">
                            <td colspan="3">No saved drafts available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // For log out notification
        document.querySelector('.sign-out-btn').addEventListener('click', function(event) {
            event.preventDefault();               
            const userConfirmed = confirm("Are you sure you want to log out?");
            
            if (userConfirmed) {
                window.location.href = "../../logins/logout.php";
            }
        });
        
        // Tab switching functionality
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Show corresponding tab content
                const tabId = tab.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html>