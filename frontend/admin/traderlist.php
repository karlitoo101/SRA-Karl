<?php
session_start();
if (!isset($_SESSION['userID']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../../logins/logout.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="traderlist.css">
    <style>
        /* Add these styles for the unread notification dot */
        .message-btn {
            position: relative;
        }

        .unread-indicator {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            background-color: #ff4757;
            border-radius: 50%;
            display: none;
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
            <a href="#" class="nav-item active" id="tradersNav">
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
            <a href="accountsetting.php" class="nav-item" id="accountSettingsNav">
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
    <div class="page-content">
        <div class="stepper-nav">
            <div class="step step-current step-active">
                <div class="step-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span>List of Traders</span>
            </div>
            <div class="step-divider">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="step step-pending">
                <div class="step-icon">
                    <i class="fa-solid fa-list"></i>
                </div>
                <span>List of Reports</span>
            </div>
            <div class="step-divider">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="step step-pending">
                <div class="step-icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <span>Report</span>
            </div>
        </div>
        <div class="page-header">
            <div>
                <h1 class="page-title">All Traders</h1>
                <p class="page-subtitle">Manage and monitor all traders in the system.</p>
            </div>
            <div class="search-container">
                <div class="search-icon">
                    <i class="fa-solid fa-search"></i>
                </div>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
        </div>
        <div class="sort-controls">
            <label for="sortSelect">Sort by:</label>
            <select id="sortSelect">
                <option value="alphabetical">Trader name (A-Z)</option>
            </select>
        </div>
        <table class="traders-table">
            <thead>
                <tr>
                    <th>Trader Name</th>
                    <th>Contact Number</th>
                    <th>Telephone Number</th>
                    <th>Region</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tradersTableBody"></tbody>
        </table>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="chat-panel" id="chatPanel">
        <div class="chat-header">
            <div class="trader-info">
                <div class="trader-avatar">
                    <span id="traderInitials">JC</span>
                </div>
                <div class="trader-details">
                    <h3 id="traderName">Jane Cooper</h3>
                    <p id="traderEmail">jane@microsoft.com</p>
                </div>
            </div>
            <div class="close-chat" id="closeChat">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div id="chat-box-body"></div>
        </div>
        <form class="chat-input" id="chat-form">
            <input type="hidden" id="sender" value="<?php echo $_SESSION['name']; ?>">
            <input type="hidden" id="receiver" value="">
            <input type="text" id="message" placeholder="Type your message..." required>
            <button type="submit" class="send-btn"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        let allTraders = [];
        let unreadMessages = {};
        const searchInput = document.querySelector('.search-input');
        const tableBody = document.getElementById("tradersTableBody");
        let currentTrader = null;

        fetch('../../backend/admin/displaytraders.php')
            .then(response => response.json())
            .then(traders => {
                allTraders = traders;
                renderTradersTable();
                // Fetch unread message counts
                checkUnreadMessages();
            })
            .catch(error => console.error('Error fetching traders:', error));

        function renderTradersTable() {
            const searchTerm = searchInput.value.toLowerCase();
            let filteredTraders = allTraders.filter(trader =>
                trader.traderName.toLowerCase().includes(searchTerm) ||
                trader.contactNumber.toLowerCase().includes(searchTerm) ||
                trader.telephoneNumber.toLowerCase().includes(searchTerm) ||
                trader.region.toLowerCase().includes(searchTerm)
            );
            filteredTraders.sort((a, b) => a.traderName.localeCompare(b.traderName));
            tableBody.innerHTML = "";
            filteredTraders.forEach(trader => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${trader.traderName}</td>
                    <td>${trader.contactNumber}</td>
                    <td>${trader.telephoneNumber}</td>
                    <td>${trader.region}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button class="view-reports-btn" data-trader="${trader.traderName}" data-region="${trader.region}">View Reports</button>
                            <button class="message-btn" data-trader="${trader.traderName}" data-region="${trader.region}">
                                <i class="fa-solid fa-comment"></i> Message
                                <span class="unread-indicator" id="unread-${trader.traderName.replace(/\s+/g, '-')}"></span>
                            </button>
                        </div>
                    </td>`;
                tableBody.appendChild(row);
            });
            attachMessageButtonListeners();
            attachViewReportsButtonListeners();
            updateUnreadIndicators();
        }

        function checkUnreadMessages() {
            $.ajax({
                url: '../../backend/messages/check_unread.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    unreadMessages = data;
                    updateUnreadIndicators();
                },
                error: function (xhr, status, error) {
                    console.error('Error checking unread messages:', error);
                }
            });
        }

        function updateUnreadIndicators() {
            // Clear all indicators first
            document.querySelectorAll('.unread-indicator').forEach(indicator => {
                indicator.style.display = 'none';
            });

            // Set indicators for traders with unread messages
            for (const trader in unreadMessages) {
                if (unreadMessages[trader] > 0) {
                    const indicatorId = `unread-${trader.replace(/\s+/g, '-')}`;
                    const indicator = document.getElementById(indicatorId);
                    if (indicator) {
                        indicator.style.display = 'block';
                    }
                }
            }
        }

        searchInput.addEventListener('input', renderTradersTable);

        document.getElementById("sortSelect").addEventListener("change", function () {
            renderTradersTable();
        });

        document.getElementById("logoutBtn").addEventListener("click", function () {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "../../logins/logout.php";
            }
        });

        function attachViewReportsButtonListeners() {
            document.querySelectorAll(".view-reports-btn").forEach(button => {
                button.addEventListener("click", function () {
                    const traderName = this.getAttribute("data-trader");
                    const region = this.getAttribute("data-region");
                    window.location.href = `trader-report-list.php?trader=${traderName}&region=${region}`;
                });
            });
        }

        function attachMessageButtonListeners() {
            document.querySelectorAll(".message-btn").forEach(button => {
                button.addEventListener("click", function () {
                    const traderNameValue = this.getAttribute("data-trader");
                    const region = this.getAttribute("data-region");

                    currentTrader = traderNameValue;

                    document.getElementById("traderName").textContent = traderNameValue;
                    document.getElementById("traderEmail").textContent = region;
                    document.getElementById("traderInitials").textContent = traderNameValue.split(" ").map(n => n[0]).join("");
                    document.getElementById("receiver").value = traderNameValue;

                    chatPanel.classList.add("active");
                    overlay.classList.add("active");

                    // Clear unread indicator
                    const indicatorId = `unread-${traderNameValue.replace(/\s+/g, '-')}`;
                    const indicator = document.getElementById(indicatorId);
                    if (indicator) {
                        indicator.style.display = 'none';
                    }

                    // Fetch messages and mark as read
                    fetchMessages(true);

                    setTimeout(scrollChatToBottom, 100);
                    setTimeout(scrollChatToBottom, 300);
                });
            });
        }

        const chatPanel = document.getElementById("chatPanel");
        const overlay = document.getElementById("overlay");
        const closeChat = document.getElementById("closeChat");

        closeChat.addEventListener("click", () => {
            chatPanel.classList.remove("active");
            overlay.classList.remove("active");
            currentTrader = null;
        });

        overlay.addEventListener("click", () => {
            chatPanel.classList.remove("active");
            overlay.classList.remove("active");
            currentTrader = null;
        });

        function fetchMessages(markAsRead = false) {
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();

            $.post('../../backend/messages/fetch_message.php', {
                sender: sender,
                receiver: receiver,
                markAsRead: markAsRead.toString()  // Convert boolean to string "true" or "false"
            }, function (data) {
                $('#chat-box-body').html(data);


                scrollChatToBottom();



                // After marking messages as read, update unread indicators
                if (markAsRead) {
                    checkUnreadMessages();
                }
            });
        }
        function scrollChatToBottom() {
            var chatBox = $('#chat-box-body');
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }

        $('#chat-form').submit(function (e) {
            e.preventDefault();
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();
            var message = $('#message').val();
            $.post('../../backend/messages/submit_message.php', { sender, receiver, message }, function () {
                $('#message').val('');
                fetchMessages();
                scrollChatToBottom();
            });
        });

        $(document).ready(function () {
            // Initial fetch without marking as read
            fetchMessages(false);

            // Set up intervals for checking messages
            setInterval(function () {
                // Only mark as read if chat panel is active
                fetchMessages(chatPanel.classList.contains('active'));
            }, 1000);

            // Check for unread messages periodically
            setInterval(checkUnreadMessages, 5000);
        });
    </script>
</body>

</html>