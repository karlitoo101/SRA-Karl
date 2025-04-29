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
            font-size: 16px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
        }

        .nav-icon1 {
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
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .content-container {
            padding: 30px 40px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            overflow-y: auto;
            height: 100vh;
        }
        
        .content-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 800px;
            padding: 30px;
            margin: 0 auto;
        }

        .content-card h2 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #e9e9e9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }

        .profile-avatar i {
            font-size: 30px;
            color: #777;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-group {
            margin-bottom: 25px;
        }

        .label {
            font-size: 12px;
            color: #777;
            margin-bottom: 8px;
        }

        .value {
            font-size: 16px;
            color: #333;
        }

        .buttons {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid #ccc;
            color: #333;
        }

        .btn-outline:hover {
            background-color: #f5f5f5;
        }

        .btn-primary {
            background-color: #00843d;
            color: white;
        }

        .btn-primary:hover {
            background-color: #006e33;
        }

        .btn-back {
            background-color: transparent;
            border: none;
            color: #666;
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 20px;
            padding: 0;
        }

        .btn-back i {
            margin-right: 8px;
        }

        .create-trader-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #00843d;
            box-shadow: 0 0 0 2px rgba(0, 132, 61, 0.1);
        }

        .form-group .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            cursor: pointer;
            color: #777;
        }

        .form-actions {
            grid-column: span 2;
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #666;
            margin-top: -15px;
            margin-bottom: 30px;
        }

        .hidden {
            display: none;
        }

        .password-requirements {
            margin-top: 8px;
            font-size: 12px;
            color: #777;
        }

        .password-requirements ul {
            list-style-type: none;
            padding-left: 0;
            margin-top: 5px;
        }

        .password-requirements li {
            margin-bottom: 3px;
            display: flex;
            align-items: center;
        }

        .password-requirements li i {
            margin-right: 5px;
            font-size: 10px;
        }

        .requirement-met {
            color: #00843d;
        }

        .requirement-unmet {
            color: #888;
        }

        .invalid-input {
            border-color: #e74c3c !important;
        }

        .requirement-met {
            color: green;
        }

        .requirement-unmet {
            color: red;
        }

        .requirement-met i {
            color: green; /* Green check mark */
        }

        .requirement-unmet i {
            color: red; /* Red 'X' icon */
        }

        /* Field note display for character limits */
        .field-note {
            font-size: 11px;
            color: #777;
            margin-top: 4px;
            font-style: italic;
        }
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-select {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: white;
            width: 100%;
            appearance: none;
            color: #888; /* Default: gray for placeholder */
        }
        .custom-select:valid {
            color: #000;
        }
        .custom-select option[value=""] {
            color: #888;
        }

        .custom-arrow {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 14px;
            color: #888;
        }
        .user-info .email {
            font-size: 13px;
            font-weight: 400; /* slightly lighter than default */
            color: #777;       /* softer color for subtle appearance */
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .create-trader-form {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                grid-column: span 1;
            }
            
            .buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .content-card {
                padding: 20px;
            }

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
            
            <a href="traderlist.php" class="nav-item" id="tradersNav">
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
            <a href="supplydemand.php" class="nav-item" id="supplyDemandNav">
                <div class="nav-item-content">
                    <div class="nav-icon1">
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
            <a href="#" class="nav-item active" id="accountSettingsNav">
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
        <!-- Admin Profile Content -->
        <div class="content-container">
            <div class="content-card" id="admin-profile-content">
                <h2>Admin Profile</h2>
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3 class="profile-name"><?php echo htmlspecialchars($_SESSION['name']); ?></h3>
                </div>
                <div class="profile-info">
                    <div class="info-group">
                        <div class="label">Email</div>
                        <div class="value"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                    </div>
                </div>
                <div class="buttons">
                    <button class="btn btn-outline" id="edit-profile-btn">Edit Profile</button>
                    <button class="btn btn-outline" id="change-password-btn">Change Password</button>
                    <button class="btn btn-primary" id="create-trader-btn">Create Trader Account</button>
                </div>
            </div>

            <!-- Edit Profile Content (Initially Hidden) -->
            <div class="content-card hidden" id="edit-profile-content">
                <button class="btn-back" id="back-from-edit">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </button>
                <h2>Edit Profile</h2>
                <form id="edit-profile-form">
                    <div class="create-trader-form">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Change Password Content (Initially Hidden) -->
            <div class="content-card hidden" id="change-password-content">
                <button class="btn-back" id="back-from-password">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </button>
                <h2>Change Password</h2>
                <form id="change-password-form">
                    <div class="create-trader-form">
                        <div class="form-group">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" id="currentPassword" name="currentPassword">
                            <i class="toggle-password fa-solid fa-eye" data-target="currentPassword"></i>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" id="newPassword" name="newPassword">
                            <i class="toggle-password fa-solid fa-eye" data-target="newPassword"></i>
                            <div class="password-requirements" id="new-password-requirements" style="display: none;">
                                Password requirements:
                                <ul>
                                    <li id="new-length-requirement"><i class="fa-solid fa-circle"></i> At least 8 characters</li>
                                    <li id="new-uppercase-requirement"><i class="fa-solid fa-circle"></i> At least 1 uppercase letter</li>
                                    <li id="new-number-requirement"><i class="fa-solid fa-circle"></i> At least 1 number</li>
                                    <li id="new-special-requirement"><i class="fa-solid fa-circle"></i> At least 1 special character</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword">
                            <i class="toggle-password fa-solid fa-eye" data-target="confirmPassword"></i>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Create Trader Account Content (Initially Hidden) -->
            <div class="content-card hidden" id="create-trader-content">
                <button class="btn-back" id="back-from-trader">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </button>
                <h2>Create New Trader Account</h2>
                <p class="form-subtitle">Enter the details to register a new trader</p>
                <form id="create-trader-form">
                    <div class="create-trader-form">
                        <!-- Company Details Section First -->
                        <div class="form-group">
                            <label for="companyName">Company Name</label>
                            <input type="text" id="companyName" name="companyName" placeholder="Enter company name" required>
                        </div>
                        <div class="form-group">
                            <label for="emailAddress">Email Address</label>
                            <input type="email" id="emailAddress" name="emailAddress" placeholder="company@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="traderContactNumber">Contact Number</label>
                            <input type="text" id="traderContactNumber" name="traderContactNumber" placeholder="(+63)" maxlength="15" oninput="validateContactNumber(this)" required>
                            <div class="field-note">Format: (+63) followed by 10 digits (11 digits total)</div>
                        </div>
                        <div class="form-group">
                            <label for="telephoneNumber">Telephone Number</label>
                            <input type="text" id="telephoneNumber" name="telephoneNumber" placeholder="Enter landline number" maxlength="9" oninput="validateTelephoneNumber(this)" required>
                            <div class="field-note">Max 9 digits</div>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label for="companyAddress">Company Address</label>
                            <input type="text" id="companyAddress" name="companyAddress" placeholder="Enter company address" required>
                        </div>
                        <div class="form-group">
                            <label for="region">Region</label>
                            <div class="custom-select-wrapper">
                                <select id="region" name="region" class="custom-select" required>
                                    <option value="" disabled selected>Select Region</option>
                                    <option value="NCR">National Capital Region (NCR)</option>
                                    <option value="CAR">Cordillera Administrative Region (CAR)</option>
                                    <option value="Region I">Region I - Ilocos Region</option>
                                    <option value="Region II">Region II - Cagayan Valley</option>
                                    <option value="Region III">Region III - Central Luzon</option>
                                    <option value="Region IV-A">Region IV-A - CALABARZON</option>
                                    <option value="MIMAROPA">MIMAROPA Region</option>
                                    <option value="Region V">Region V - Bicol Region</option>
                                    <option value="Region VI">Region VI - Western Visayas</option>
                                    <option value="Region VII">Region VII - Central Visayas</option>
                                    <option value="Region VIII">Region VIII - Eastern Visayas</option>
                                    <option value="Region IX">Region IX - Zamboanga Peninsula</option>
                                    <option value="Region X">Region X - Northern Mindanao</option>
                                    <option value="Region XI">Region XI - Davao Region</option>
                                    <option value="Region XII">Region XII - SOCCSKSARGEN</option>
                                    <option value="CARAGA">CARAGA Region</option>
                                    <option value="BARMM">Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)</option>
                                </select>
                                <span class="custom-arrow">&#9662;</span>
                            </div>
                        </div>                        
                        
                        <!-- Password Fields Last -->
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                            <i class="toggle-password fa-solid fa-eye" data-target="password"></i>
                            <div class="password-requirements" id="trader-password-requirements" style="display: none;">
                                Password requirements:
                                <ul>
                                    <li id="trader-length-requirement"><i class="fa-solid fa-circle"></i> At least 8 characters</li>
                                    <li id="trader-uppercase-requirement"><i class="fa-solid fa-circle"></i> At least 1 uppercase letter</li>
                                    <li id="trader-number-requirement"><i class="fa-solid fa-circle"></i> At least 1 number</li>
                                    <li id="trader-special-requirement"><i class="fa-solid fa-circle"></i> At least 1 special character</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirmTraderPassword">Confirm Password</label>
                            <input type="password" id="confirmTraderPassword" name="confirmTraderPassword" placeholder="Confirm password" required>
                            <i class="toggle-password fa-solid fa-eye" data-target="confirmTraderPassword"></i>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', () => {
                const passwordField = document.getElementById(icon.getAttribute('data-target'));
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Navigation between views
        document.getElementById('edit-profile-btn').addEventListener('click', () => {
            document.getElementById('admin-profile-content').classList.add('hidden');
            document.getElementById('edit-profile-content').classList.remove('hidden');
        });

        document.getElementById('back-from-edit').addEventListener('click', () => {
            document.getElementById('edit-profile-content').classList.add('hidden');
            document.getElementById('admin-profile-content').classList.remove('hidden');
        });

        document.getElementById('change-password-btn').addEventListener('click', () => {
            document.getElementById('admin-profile-content').classList.add('hidden');
            document.getElementById('change-password-content').classList.remove('hidden');
        });

        document.getElementById('back-from-password').addEventListener('click', () => {
            document.getElementById('change-password-content').classList.add('hidden');
            document.getElementById('admin-profile-content').classList.remove('hidden');
        });

        document.getElementById('create-trader-btn').addEventListener('click', () => {
            document.getElementById('admin-profile-content').classList.add('hidden');
            document.getElementById('create-trader-content').classList.remove('hidden');
        });

        document.getElementById('back-from-trader').addEventListener('click', () => {
            document.getElementById('create-trader-content').classList.add('hidden');
            document.getElementById('admin-profile-content').classList.remove('hidden');
        });

        // Show password requirements on focus
        document.getElementById('newPassword').addEventListener('focus', () => {
            document.getElementById('new-password-requirements').style.display = 'block';
        });
        
        document.getElementById('password').addEventListener('focus', () => {
            document.getElementById('trader-password-requirements').style.display = 'block';
        });

        // Validate contact number for trader with parentheses
        function validateContactNumber(input) {
            // Strip all non-digits except parentheses and +
            let value = input.value.replace(/[^\d+()]/g, '');
            
            // Ensure it starts with (+63)
            if (!value.startsWith('(+63)')) {
                value = '(+63)';
            }
            
            // Extract digits after (+63)
            let digits = value.substring(5).replace(/\D/g, '');
            
            // Limit to 10 digits after (+63)
            if (digits.length > 10) {
                digits = digits.substring(0, 10);
            }
            
            // Format as (+63) followed by digits
            input.value = '(+63)' + digits;
        }

        // Validate telephone number (9 digits max)
        function validateTelephoneNumber(input) {
            // Allow only numbers
            input.value = input.value.replace(/[^\d]/g, '');
            
            // Limit to 9 digits
            if (input.value.length > 9) {
                input.value = input.value.slice(0, 9);
            }
        }

        function checkPasswordRequirements(password, prefix) {
            const lengthMet = password.length >= 8;
            const uppercaseMet = /[A-Z]/.test(password);
            const numberMet = /\d/.test(password);
            const specialMet = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            updateRequirementUI(`${prefix}-length-requirement`, lengthMet);
            updateRequirementUI(`${prefix}-uppercase-requirement`, uppercaseMet);
            updateRequirementUI(`${prefix}-number-requirement`, numberMet);
            updateRequirementUI(`${prefix}-special-requirement`, specialMet);

            return lengthMet && uppercaseMet && numberMet && specialMet;
        }
        
        function updateRequirementUI(elementId, isMet) {
            const element = document.getElementById(elementId);
            if (isMet) {
                element.classList.add('requirement-met');
                element.classList.remove('requirement-unmet');
                element.querySelector('i').className = 'fa-solid fa-check';
            } else {
                element.classList.add('requirement-unmet');
                element.classList.remove('requirement-met');
                element.querySelector('i').className = 'fa-solid fa-circle';
            }
        }

        // Listen for password input changes
        document.getElementById('newPassword').addEventListener('input', function() {
            checkPasswordRequirements(this.value, 'new');
        });

        document.getElementById('password').addEventListener('input', function() {
            checkPasswordRequirements(this.value, 'trader');
        });

        // Form submission handlers
        document.getElementById('edit-profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
    
            const email = document.getElementById('email').value.trim();

            fetch('../../backend/admin/changeadminemail.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `email=${encodeURIComponent(email)}`
            })
            .then(res => res.text())
            .then(data => {
                alert(data);
                document.getElementById('edit-profile-content').classList.add('hidden');
                document.getElementById('admin-profile-content').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred while updating the profile.");
            });
        });


        document.getElementById('change-password-form').addEventListener('submit', function(e) {
             e.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value.trim();
            const newPassword = document.getElementById('newPassword').value.trim();
            const confirmPassword = document.getElementById('confirmPassword').value.trim();

            // Check if passwords match
            if (newPassword !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            // Check password requirements
            if (!checkPasswordRequirements(newPassword, 'new')) {
                alert('Password does not meet all requirements!');
                return;
            }

            const formData = new URLSearchParams();
            formData.append('currentPassword', currentPassword);
            formData.append('newPassword', newPassword);
            formData.append('confirmPassword', confirmPassword);

            fetch('../../backend/admin/changeadminpassword.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
            .then(res => res.text())
            .then(response => {
                if (response === "success") {
                    alert('Password changed successfully!');
                    document.getElementById('change-password-content').classList.add('hidden');
                    document.getElementById('admin-profile-content').classList.remove('hidden');
                } else {
                    alert(response); // Show error message from backend
                }
            })
            .catch(err => {
                console.error("Error updating password:", err);
                alert("An error occurred while changing the password.");
            });
        });

        document.getElementById('create-trader-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmTraderPassword').value;

            // Check if passwords match
            if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
            }

            // Check password requirements
            if (!checkPasswordRequirements(password, 'trader')) {
            alert('Password does not meet all requirements!');
            return;
            }

            // Collect form data
            const formData = new FormData(this);

        fetch('../../backend/admin/createtraderaccount.php', {
        method: 'POST',
        body: formData
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
            alert(result.message || 'Trader account created successfully!');
            document.getElementById('create-trader-form').reset();
            document.getElementById('create-trader-content').classList.add('hidden');
            document.getElementById('admin-profile-content').classList.remove('hidden');
        } else {
            alert(result.error || 'Something went wrong!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was a problem creating the trader account.');
    });
});
    document.getElementById("logoutBtn").addEventListener("click", function() {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "../../logins/logout.php";
            }
        });

    </script>
</body>
</html>