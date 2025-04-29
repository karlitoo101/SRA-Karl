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
    <title>Sugar Regulatory Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/traderdashboard.css">

</head>

<body>
    <header>
        <img src="../../resources/SRA_thumbnail.png" alt="SRA Logo" class="logo">
        <img src="../../resources/REPUBLICOFPH.png" alt="SRA Logo" class="logo secondLogo">
        <nav>
            <a href="#" class="active">HOME</a>
            <a href="reportdraft.php">DRAFTS</a>
            <button class="sign-out-btn">Sign out</button>
        </nav>
    </header>

    <main>
        <div class="trader-info">
            Logged in as: <span><?php echo htmlspecialchars($_SESSION['name']); ?></span> </div>
        <div class="welcome-section">
            <div class="title-container">
                <h1>Welcome to the</h1>
                <span class="green-highlight">Sugar Regulatory Administration</span>
            </div>
            <p>Monitoring System: Efficiently track and manage trader reports with real-time data and compliance
                updates.</p>
            <button class="create-report-btn" onclick="window.location.href='createreport.php'">Create report</button>
        </div>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-file"></i>
                </div>
                <h3>Easy Reporting</h3>
                <p>Submit and manage reports effortlessly with our smooth, trader-friendly system built for regulatory
                    compliance.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h3>Real-time Updates</h3>
                <p>Stay updated with real-time notifications and instant chats for seamless
                    communication.</p>
            </div>
        </div>

        <div class="spacer"></div>
    </main>

    <footer id="footer">
        <div class="footer-content">
            <div class="footer-description">
                The Sugar Regulatory Administration (SRA) monitors and regulates the sugar industry to ensure fair trade
                practices and market stability.
            </div>

            <div class="footer-links">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
                <i class="fa-brands fa-facebook"></i>
            </div>

            <div class="footer-bottom">
                SRA © 2025, All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Overlay div for blur effect -->
    <div class="overlay" id="overlay"></div>

    <!-- Chat Bubble -->
    <div class="chat-bubble">
        <i class="fa-solid fa-comment"></i>
        <span class="notification-dot" style="display: none;"></span>
    </div>


    <!-- Chat Window -->
    <div class="chat-window">
        <div class="chat-header">
            <h3><i class="fa-solid fa-circle"></i> SRA Chat Support</h3>
            <div class="chat-close">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="chat-box-body" id="chat-box-body">
            <!-- Chat messages will be loaded here -->

        </div>
        <form class="chat-input" id="chat-form">
            <input type="hidden" id="sender" value="<?php echo $_SESSION['name']; ?>">
            <input type="hidden" id="receiver" value="<?php echo $_SESSION['receiverName']; ?>">
            <input type="text" id="message" placeholder="Type your message..." required>
            <button type="submit" class="send-btn"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // For log out notif
        document.querySelector('.sign-out-btn').addEventListener('click', function (event) {
            event.preventDefault();
            const userConfirmed = confirm("Are you sure you want to log out?");

            if (userConfirmed) {
                window.location.href = "../../logins/logout.php";
            }
        });

        // Show footer only when scrolling down
        window.addEventListener('scroll', function () {
            const footer = document.getElementById('footer');
            const scrollPosition = window.scrollY;

            // Show footer when scrolled enough
            if (scrollPosition > 100) {
                footer.style.display = 'block';
            } else {
                footer.style.display = 'none';
            }
        });

        // Force a small scroll event to check if scrollbar is available
        window.addEventListener('load', function () {
            // Add enough content to make page scrollable if it isn't already
            const body = document.body;
            const html = document.documentElement;
            const height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

            if (height <= window.innerHeight) {
                // Page isn't tall enough to scroll, add some padding to main
                document.querySelector('main').style.paddingBottom = '300px';
            }
        });

        // Chat functionality
        $(document).ready(function () {
            const chatBubble = $('.chat-bubble');
            const chatWindow = $('.chat-window');
            const chatClose = $('.chat-close');
            const overlay = $('#overlay');

            // Toggle chat window visibility
            chatBubble.on('click', function () {
                chatWindow.css('display', 'flex');
                chatBubble.css('display', 'none');
                overlay.css('display', 'block');
                $('#message').focus();

                $('.notification-dot').hide(); // 👈 Hide the dot when chat opens
            });

            // Close chat window
            chatClose.on('click', function () {
                chatWindow.css('display', 'none');
                chatBubble.css('display', 'flex');
                overlay.css('display', 'none');
            });

            // Also close chat when clicking overlay
            overlay.on('click', function (e) {
                if (e.target === overlay[0]) {
                    chatWindow.css('display', 'none');
                    chatBubble.css('display', 'flex');
                    overlay.css('display', 'none');
                }
            });

            // Function to fetch messages
            function fetchMessages() {
                var sender = $('#sender').val();
                var receiver = $('#receiver').val();
                var chatBox = $('#chat-box-body');
                var chatWindow = $('.chat-window');
                var notificationDot = $('.notification-dot');

                $.ajax({
                    url: '../../backend/messages/fetch_message.php',
                    type: 'POST',
                    data: { sender: sender, receiver: receiver, markAsRead: true },

                    success: function (data) {
                        // Compare new data with old data
                        var prevContent = chatBox.html();
                        chatBox.html(data);

                        // Check for latest message sender if content changed
                        if (data !== prevContent) {
                            scrollChatToBottom();

                            // Use jQuery to find the last message container
                            var lastSender = $('#chat-box-body .message-container:last .sender-name').text().trim();

                            // If it's not the current user, and chat is closed, show the dot
                            if (lastSender === receiver && !chatWindow.is(':visible')) {
                                notificationDot.show();
                            }
                        }
                    }
                });
            }


            // Function to scroll the chat box to the bottom
            function scrollChatToBottom() {
                var chatBox = $('#chat-box-body');
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            }

            $(document).ready(function () {
                fetchMessages();
                setInterval(fetchMessages, 3000);
            });

            // Submit the chat message
            $('#chat-form').submit(function (e) {
                e.preventDefault();
                var sender = $('#sender').val();
                var receiver = $('#receiver').val();
                var message = $('#message').val();

                var sendBtn = $('.send-btn');
                sendBtn.prop('disabled', true); // Disable button

                $.ajax({
                    url: '../../backend/messages/submit_message.php',
                    type: 'POST',
                    data: { sender: sender, receiver: receiver, message: message },
                    success: function () {
                        $('#message').val('');
                        fetchMessages(); // Fetch after sending
                    },
                    complete: function () {
                        sendBtn.prop('disabled', false); // Re-enable button
                    }
                });
            });

        });
    </script>
</body>

</html>