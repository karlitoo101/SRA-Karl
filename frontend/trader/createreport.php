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
    <title>SRA Trader's Activity Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .header {
            background-color: #008f00;
            color: white;
            padding: 15px 0;
            position: relative;
        }
        
        .back-button {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 24px;
            text-decoration: none;
        }
        
        .header-text {
            text-align: center;
        }
        
        .header-text h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .header-text h2 {
            font-size: 14px;
            font-weight: normal;
        }
        
        .container {
            background-color: white;
            padding: 20px;
        }
        
        .trader-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            color: #008f00;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .section-title i {
            margin-right: 5px;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        
        .form-group {
            flex: 1;
            min-width: 300px;
            margin-bottom: 15px;
            padding-right: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }
        
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .category-options {
            margin-top: 10px;
        }
        
        .radio-option {
            display: inline-block;
            margin-right: 15px;
        }
        
        .stock-balance-input {
            width: 200px !important;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #ffffff;
        }
        
        /* Monthly Report Period Selection */
        .report-period {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .period-selectors {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .period-label {
            font-weight: bold;
            font-size: 14px;
            margin-right: 10px;
        }
        
        .period-select {
            flex: 1;
            max-width: 200px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px 5px;
            text-align: center;
            position: relative;
        }
        
        th {
            background-color: #f2f2f2;
            vertical-align: middle;
        }
        
        .purchases-header, .utilization-header {
            background-color: #dff0d8;
            text-align: center;
            font-weight: bold;
        }
        
        .col-cy {
            width: 8%;
        }
        
        .col-normal {
            width: 8%;
        }
        
        /* Clean table input styling */
        table input[type="text"] {
            width: 100%;
            height: 100%;
            padding: 2px;
            border: none;
            text-align: center;
            background: transparent;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
        
        /* Add a small height to table cells to accommodate input */
        table td {
            height: 30px;
        }
        
        .note {
            background-color: #ebebeb;
            padding: 10px;
            border-radius: 4px;
            margin-top: 15px;
            display: flex;
            align-items: center;
            font-size: 12px;
        }
        
        .note i {
            margin-right: 5px;
            font-size: 16px;
        }
        
        .month-display {
            font-weight: bold;
            text-align: center;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: bold;
        }
        
        .btn i {
            margin-right: 5px;
        }
        
        .btn-draft {
            background-color: #FFDD00;
            color: black;
        }
        
        .btn-submit {
            background-color: #008000;
            color: white;
        }
        
        @media (max-width: 768px) {
            .form-group {
                flex: 100%;
                padding-right: 0;
            }
            
            table {
                font-size: 10px;
            }
            
            th, td {
                padding: 4px 2px;
            }
            
            .period-selectors {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .period-select {
                max-width: 100%;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="traderdashboard.php" class="back-button">&#10094;</a>
        <div class="header-text">
            <h1>SRA Trader's Activity Report</h1>
            <h2>Sugar Regulatory Administration</h2>
        </div>
    </div>
    
    <div class="container">
        <div class="trader-section">
            <div class="section-title">
              <i class="fa-regular fa-file"></i> Trader Information
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="traderName">Name of Trader</label>
                    <input type="text" id="traderName" placeholder="e.g. Fn Mi Ln">
                </div>
                
                <div class="form-group">
                    <label for="cropYear">Crop Year</label>
                    <input type="text" id="cropYear" placeholder="YYYY-YYYY">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="businessAddress">Business Address</label>
                    <input type="text" id="businessAddress" placeholder="Enter permanent business address">
                </div>
                
                <div class="form-group">
                    <label for="contactNumber">Contact Number</label>
                    <input type="text" id="contactNumber" placeholder="Enter contact number">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Select applicable category:</label>
                    <div class="category-options">
                        <span class="radio-option">
                            <input type="radio" id="sugar" name="category">
                            <label for="sugar">Sugar</label>
                        </span>
                        <span class="radio-option">
                            <input type="radio" id="molasses" name="category">
                            <label for="molasses">Molasses</label>
                        </span>
                        <span class="radio-option">
                            <input type="radio" id="muscovado" name="category">
                            <label for="muscovado">Muscovado</label>
                        </span>
                        <span class="radio-option">
                            <input type="radio" id="fructose" name="category">
                            <label for="fructose">Fructose</label>
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="stockBalance">Previous Crop Stock Balance</label>
                    <input type="text" id="stockBalance" class="stock-balance-input">
                </div>
            </div>
        </div>
        
        <!-- Monthly Report Period Selection -->
        <div class="report-period">
            <div class="period-selectors">
                <span class="period-label">Reporting Period:</span>
                <div class="period-select">
                    <select id="reportMonth">
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                
                <div class="period-select">
                    <select id="reportYear">
                        <option value="2020-2021">2020-2021</option>
                        <option value="2021-2022">2021-2022</option>
                        <option value="2022-2023">2022-2023</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025" selected>2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                        <option value="2027-2028">2027-2028</option>
                        <option value="2028-2029">2028-2029</option>
                        <option value="2029-2030">2029-2030</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Monthly Report Table Section -->
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" class="col-cy">CY/MONTH</th>
                        <th colspan="4" class="purchases-header">PURCHASES (VOLUME)</th>
                        <th rowspan="2">Pls specify Sources & other information</th>
                        <th colspan="5" class="utilization-header">UTILIZATION (VOLUME)</th>
                        <th rowspan="2">Stock Balance</th>
                    </tr>
                    <tr>
                        <th>IMPORTATION</th>
                        <th>LOCAL MILLS</th>
                        <th>LOCAL TRADERS</th>
                        <th>AUCTION (BOC)</th>
                        <th>OWN USED/ MANUFACTURING</th>
                        <th>SALE TO DOMESTIC MARKET</th>
                        <th>EXPORT TO US MARKET</th>
                        <th>EXPORT TO WORLD MARKET</th>
                        <th>Clients & other information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="month-display" id="selectedMonth">April 2024-2025</td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                        <td><input type="text"></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="note">
              <i class="fa-solid fa-circle-info"></i> Submit Activity Reports by March 15 (for Sept–Feb) and by September 15 (for Mar–Aug) each CY.
            </div>

            <div class="form-row" style="margin-top: 20px;">
              <div class="form-group" style="flex: 1;">
                <!-- Checkbox and label -->
                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px;">
                  <input type="checkbox" id="certify" style="margin-top: 3px;">
                  <label for="certify" style="font-weight: normal; font-size: 14px; line-height: 1.4;">
                    I certify that the information in this report is true and correct to the best of my knowledge. 
                    I understand that false statements may result in legal action.
                  </label>
                </div>
            
                <!-- Remarks -->
                <div>
                  <label for="remarks" style="font-weight: bold; font-size: 14px;">Remarks</label>
                  <textarea id="remarks" style="width: 100%; height: 60px; padding: 8px; border: 1px solid #605f5f; border-radius: 4px; resize: vertical;"></textarea>
                </div>
              </div>
            </div>
            
            <!-- Footer Info + Buttons -->
            <div style="margin-top: 20px; padding: 15px; background-color: #E6FFE6; border-radius: 4px; display: flex; align-items: center; border-top: 1px solid #ddd;">
              <div style="display: flex; align-items: center; margin-right: auto;">
                <i class="fa-solid fa-circle-info" style="color: #008000; margin-right: 8px;"></i>
                <span style="font-size: 14px;">This report is submitted in accordance with SRA regulations</span>
              </div>
            
              <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-draft">
                  <i class="fa-regular fa-floppy-disk"></i> Save as Draft
                </button>
            
                <button type="button" class="btn btn-submit">
                  <i class="fa-solid fa-paper-plane"></i> Submit
                </button>
              </div>
            </div>
        </div>
    </div>

    <script>
        // Simple script to update the displayed month when selections change
        document.getElementById('reportMonth').addEventListener('change', updateDisplayedMonth);
        document.getElementById('reportYear').addEventListener('change', updateDisplayedMonth);

        function updateDisplayedMonth() {
            const monthSelect = document.getElementById('reportMonth');
            const yearSelect = document.getElementById('reportYear');
            const monthDisplay = document.getElementById('selectedMonth');
            
            if(monthSelect.value && yearSelect.value) {
                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                
                const selectedMonth = monthNames[parseInt(monthSelect.value) - 1];
                monthDisplay.textContent = `${selectedMonth} ${yearSelect.value}`;
            } else {
                monthDisplay.textContent = "Select Month and Year";
            }
        }
        
        // Initialize with default values if present
        window.onload = function() {
            updateDisplayedMonth();
        }
    </script>
</body>
</html>