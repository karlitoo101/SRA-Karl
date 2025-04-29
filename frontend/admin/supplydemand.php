<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
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
    <title>Sugar Supply & Demand Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            height: 100vh;
            background-color: #f7f9fc;
            overflow: hidden;
        }
        
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            border-right: none;
        }
        
        .logo {
            padding: 0 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo img {
            height: 100px;
            width: auto;
        }
        
        .nav-section {
            margin-bottom: 16px;
        }
        
        .nav-group {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8a94a6;
            font-weight: 600;
            padding: 8px 24px;
            margin-bottom: 8px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            cursor: pointer;
            color: #4d5a6f;
            text-decoration: none;
            justify-content: space-between;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover {
            background-color: #f3f4f6;
            color: #1e293b;
        }
        
        .nav-item.active {
            background-color: #e9f7ee;
            color: #00843d;
            border-left: 3px solid #00843d;
            font-weight: 600;
        }
        
        .nav-item-content {
            display: flex;
            align-items: center;
        }
        
        .nav-icon {
            font-size: 18px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
        }
        
        .nav-item span {
            font-size: 14px;
        }
        
        .arrow-icon {
            font-size: 12px;
            opacity: 0.6;
        }
        
        .user-profile {
            margin-top: auto;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            border-top: 1px solid #f1f1f5;
            background-color: #fafbfc;
        }
        
        .user-profile .avatar {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background-color: #e9f7ee;
            color: #00843d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 16px;
        }
        
        .user-profile .user-info {
            flex-grow: 1;
        }
        
        .user-profile .name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }
        
        .user-profile .role {
            font-size: 12px;
            color: #64748b;
        }
        
        .user-profile .logout {
            cursor: pointer;
            color: #64748b;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .user-profile .logout:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }
        
        .main-content {
            flex-grow: 1;
            padding: 24px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .stepper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }
        
        .step {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
        }
        
        .step.active {
            background-color: #00843d;
        }
        
        .step-connector {
            width: 40px;
            height: 2px;
            background-color: #e2e8f0;
            margin: 0 2px;
        }
        
        .step-label {
            position: absolute;
            width: 120px;
            text-align: center;
            top: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            transform: translateX(-50%);
            left: 50%;
            color: #64748b;
        }
        
        .step.active .step-label {
            color: #00843d;
        }
        
        .dashboard-title {
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 5px;
            color: #1e293b;
        }
        
        .dashboard-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
        }
        
        .dashboard-content {
            background-color: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            overflow-y: auto;
        }
        
        .graphs-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .graph-card {
            background-color: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }
        
        .graph-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .graph-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }
        
        .add-data-btn {
            padding: 6px 12px;
            background-color: #00843d;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .add-data-btn:hover {
            background-color: #006e33;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }
        
        .tab {
            padding: 6px 14px;
            background-color: #f1f5f9;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .tab.active {
            background-color: #e9f7ee;
            color: #00843d;
        }
        
        .graph-placeholder {
            height: 180px;
            background-color: #f8fafc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            border: 1px dashed #e2e8f0;
        }
        
        /* Data Form Styles */
        .data-form {
            width: 100%;
        }
        
        .form-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .data-table .header-row {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }
        
        .data-table td {
            padding: 16px;
            font-size: 14px;
            color: #1e293b;
        }
        
        .data-table .sugar-type {
            font-weight: 600;
            color: #1e293b;
        }
        
        .input-field {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #00843d;
            box-shadow: 0 0 0 2px rgba(0, 132, 61, 0.1);
        }
        
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-back {
            background-color: #f1f5f9;
            color: #1e293b;
            border: 1px solid #e2e8f0;
        }
        
        .btn-back:hover {
            background-color: #e2e8f0;
        }
        
        .btn-primary {
            background-color: #00843d;
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #006e33;
        }
        
        .action-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            color: #64748b;
            cursor: pointer;
        }
        
        .action-icon:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }
        
        /* Hide non-active views */
        .view {
            display: none;
        }
        
        .view.active {
            display: block;
        }
        .user-info .email {
            font-size: 13px;
            font-weight: 400; /* slightly lighter than default */
            color: #777;       /* softer color for subtle appearance */
            margin-top: 2px;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="../../resources/SRA_thumbnail.png" alt="Agricultural Logo">
        </div>
        
        <div class="nav-section">
            <div class="nav-group">DASHBOARD</div>
            
            <a href="traderlist.php" class="nav-item">
                <div class="nav-item-content">
                    <div class="nav-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span>Traders</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>

            <a href="#" class="nav-item active">
                <div class="nav-item-content">
                    <div class="nav-icon">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                    </div>
                    <span>Supply and Demand</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            
        </div>
        
        <div class="nav-section">
            <div class="nav-group">PREFERENCES</div>
            <a href="accountsetting.php" class="nav-item">
                <div class="nav-item-content">
                    <div class="nav-icon">
                        <i class="fa-solid fa-gear"></i>
                    </div>
                    <span>Account Settings</span>
                </div>
                <div class="arrow-icon">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
        </div>
        
        <div class="user-profile">
            <div class="avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="user-info">
                <div class="name"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
                <div class="email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                <div class="role">Administrator</div>
            </div>
            <div class="logout" id="logoutBtn">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <div class="stepper">
            <div class="step active" id="graphsStep">
                <div class="step-label">Graphs</div>
            </div>
            <div class="step-connector"></div>
            <div class="step" id="addDataStep">
                <div class="step-label">Add data</div>
            </div>
        </div>
        
        <!-- Forecast View -->
        <div class="view active" id="graphsView">
            <div class="dashboard-title">Sugar Supply & Demand Forecast</div>
            <div class="dashboard-subtitle">Current supply and demand trends</div>
            
            <div class="dashboard-content">
                <div class="graphs-container">
                    <!-- Raw Sugar Graph Card -->
                    <div class="graph-card">
                        <div class="graph-header">
                            <div class="graph-title">Raw Sugar</div>
                            <button class="add-data-btn" id="addRawDataBtn">
                                <i class="fa-solid fa-plus"></i> Add Data
                            </button>
                        </div>
                        <div class="tabs">
                            <div class="tab active">Supply</div>
                            <div class="tab">Demand</div>
                        </div>
                        <div class="graph-placeholder">
                            <span>Graph Area</span>
                        </div>
                    </div>
                    
                    <!-- Refined Sugar Graph Card -->
                    <div class="graph-card">
                        <div class="graph-header">
                            <div class="graph-title">Refined Sugar</div>
                            <button class="add-data-btn" id="addRefinedDataBtn">
                                <i class="fa-solid fa-plus"></i> Add Data
                            </button>
                        </div>
                        <div class="tabs">
                            <div class="tab active">Supply</div>
                            <div class="tab">Demand</div>
                        </div>
                        <div class="graph-placeholder">
                            <span>Graph Area</span>
                        </div>
                    </div>
                    
                    <!-- Actual Graph Card -->
                    <div class="graph-card">
                        <div class="graph-header">
                            <div class="graph-title">Actual</div>
                        </div>
                        <div class="graph-placeholder">
                            <span>Graph Area</span>
                        </div>
                    </div>
                    
                    <!-- Forecast Graph Card -->
                    <div class="graph-card">
                        <div class="graph-header">
                            <div class="graph-title">Forecast</div>
                        </div>
                        <div class="graph-placeholder">
                            <span>Graph Area</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Data View -->
        <div class="view" id="addDataView">
            <div class="dashboard-title">Add New Data</div>
            <div class="dashboard-subtitle">Input your supply and demand data</div>
            
            <div class="dashboard-content">
                <div class="data-form">
                    <div class="form-title">Upload new total supply and demand data</div>
                    
                    <table class="data-table">
                        <thead>
                            <tr class="header-row">
                                <th style="width: 25%">SUGAR TYPE</th>
                                <th style="width: 25%">Metric tons</th>
                                <th style="width: 25%">LKG</th>
                                <th style="width: 20%">Week</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="sugar-type">RAW SUGAR</td>
                                <td>
                                    <input type="text" class="input-field" placeholder="Enter value">
                                </td>
                                <td>
                                    <input type="text" class="input-field" placeholder="Enter value">
                                </td>
                                <td>
                                    <input type="text" class="input-field" placeholder="Enter week">
                                </td>
                                <td>
                                    <div class="action-icon">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="sugar-type">REFINED SUGAR</td>
                                <td>
                                    <input type="text" class="input-field" placeholder="Enter value">
                                </td>
                                <td>
                                    <input type="text" class="input-field" placeholder="Enter value">
                                </td>
                                <td>
                                    <input type="text" class="input-field" placeholder="Enter week">
                                </td>
                                <td>
                                    <div class="action-icon">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="action-buttons">
                        <button class="btn btn-back" id="backBtn">
                            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Back
                        </button>
                        <button class="btn btn-primary" id="uploadBtn">
                            <i class="fa-solid fa-upload" style="margin-right: 6px;"></i> Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // View and Stepper Management
        const graphsStep = document.getElementById('graphsStep');
        const addDataStep = document.getElementById('addDataStep');
        const graphsView = document.getElementById('graphsView');
        const addDataView = document.getElementById('addDataView');
        
        function showGraphsView() {
            // Update stepper
            graphsStep.classList.add('active');
            addDataStep.classList.remove('active');
            
            // Update views
            graphsView.classList.add('active');
            addDataView.classList.remove('active');
        }
        
        function showAddDataView() {
            // Update stepper
            graphsStep.classList.remove('active');
            addDataStep.classList.add('active');
            
            // Update views
            graphsView.classList.remove('active');
            addDataView.classList.add('active');
        }
        
        // Add event listeners for navigation
        graphsStep.addEventListener('click', showGraphsView);
        addDataStep.addEventListener('click', showAddDataView);
        
        // Add Data button event listeners
        document.getElementById('addRawDataBtn').addEventListener('click', showAddDataView);
        document.getElementById('addRefinedDataBtn').addEventListener('click', showAddDataView);
        
        // Back button event listener
        document.getElementById('backBtn').addEventListener('click', showGraphsView);
        
        // Upload button action
        document.getElementById('uploadBtn').addEventListener('click', function() {
            alert('Data uploaded successfully!');
            showGraphsView();
        });
        
        // Tab switching functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs in the same group
                const tabs = this.parentElement.querySelectorAll('.tab');
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
            });
        });
        
        // Logout functionality
        document.getElementById("logoutBtn").addEventListener("click", function() {
                  const confirmLogout = confirm("Are you sure you want to log out?");
                  if (confirmLogout) {
                    window.location.href = "../../logins/logout.php";
                  }
                });
    </script>

</body>
</html>