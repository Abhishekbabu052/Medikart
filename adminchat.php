<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");


// Optional: Add admin login check here
// if (!isset($_SESSION['admin_logged_in'])) { header("Location: admin_login.php"); exit(); }

// Handle admin reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply']) && isset($_POST['receiver'])) {
    $sender = 'admin';
    $receiver = $_POST['receiver'];
    $message = trim($_POST['reply']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO replay (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $sender, $receiver, $message);
        $stmt->execute();
        $stmt->close();
    }
}

// Get distinct users who messaged admin
$userList = array();
$sql = "SELECT DISTINCT sender FROM replay WHERE receiver = 'admin'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $userList[] = $row['sender'];
}

// Get selected user chat
$selectedUser = isset($_GET['user']) ? $_GET['user'] : (count($userList) > 0 ? $userList[0] : null);
$chatHistory = array();

if ($selectedUser) {
    $stmt = $conn->prepare("SELECT sender, message, sent_at FROM replay WHERE (sender = ? AND receiver = 'admin') OR (sender = 'admin' AND receiver = ?) ORDER BY sent_at ASC");
    $stmt->bind_param("ss", $selectedUser, $selectedUser);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($chat_sender, $chat_message, $chat_time);

    while ($stmt->fetch()) {
        $chatHistory[] = array(
            'sender' => $chat_sender,
            'message' => $chat_message,
            'time' => $chat_time
        );
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Chat Panel</title>
    <style>
        /* Your Teal Color Scheme */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body { 
            background-color: #f0fdfd; /* Light Aqua Background */
            color: #333; /* Dark Text */
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
        }
        
        h2 {
            color: #0b7c88; /* Main Teal */
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
        }
        
        /* Sidebar with User List */
        .user-sidebar {
            width: 300px;
            background-color: #ffffff; /* White */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            height: fit-content;
        }
        
        .user-sidebar h3 {
            color: #0b7c88; /* Teal */
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .user-list {
            margin-bottom: 20px;
        }
        
        .user-option {
            display: block;
            padding: 12px 15px;
            margin-bottom: 8px;
            background-color: #f9f9f9; /* Off-white */
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .user-option:hover {
            background-color: #eef9fa; /* Light Aqua */
            border-left-color: #0b7c88; /* Teal */
        }
        
        .user-option.active {
            background: linear-gradient(135deg, #00b7c5, #0b7c88); /* Teal Gradient */
            color: white;
            border-left-color: #0995a4; /* Hover Aqua */
        }
        
        /* Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #00798c, #0b7c88); /* Teal Gradient */
            color: white;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
        }
        
        .chat-header h3 {
            font-weight: 500;
        }
        
        .chat-box { 
            border: 1px solid #ddd; 
            padding: 20px;
            flex: 1;
            background-color: #ffffff; /* White */
            border-radius: 0 0 12px 12px;
            height: 400px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        
        .chat-message { 
            margin-bottom: 15px;
            padding: 12px 16px;
            border-radius: 18px;
            max-width: 70%;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .admin-message {
            background: linear-gradient(135deg, #00b7c5, #00798c); /* Aqua Gradient */
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }
        
        .user-message {
            background-color: #f9f9f9; /* Off-white */
            border: 1px solid #eee;
            border-bottom-left-radius: 4px;
        }
        
        .chat-message strong { 
            color: inherit;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .chat-message em { 
            color: inherit;
            font-size: 0.75rem;
            opacity: 0.7;
            display: block;
            text-align: right;
            margin-top: 5px;
        }
        
        /* Reply Form */
        .reply-form {
            background-color: #ffffff; /* White */
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        
        textarea { 
            width: 100%; 
            height: 80px; 
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            transition: border-color 0.3s;
        }
        
        textarea:focus {
            outline: none;
            border-color: #0b7c88; /* Teal */
            box-shadow: 0 0 0 2px rgba(11, 124, 136, 0.1);
        }
        
        button { 
            padding: 12px 30px; 
            margin-top: 10px; 
            background: linear-gradient(135deg, #00b7c5, #0b7c88); /* Teal Gradient */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        button:hover {
            background: linear-gradient(135deg, #0995a4, #0b7c88); /* Hover Aqua */
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .no-users {
            text-align: center;
            padding: 40px;
            background-color: #ffffff; /* White */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            color: #666;
        }
        
        /* Scrollbar Styling */
        .chat-box::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .chat-box::-webkit-scrollbar-thumb {
            background: #0b7c88; /* Teal */
            border-radius: 10px;
        }
        
        .chat-box::-webkit-scrollbar-thumb:hover {
            background: #0995a4; /* Hover Aqua */
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .user-sidebar {
                width: 100%;
            }
            
            .chat-message {
                max-width: 85%;
            }
        }
.admin-symbol {
    position: fixed;
    top: 20px;
    right: 20px;
    text-decoration: none;
    background: linear-gradient(135deg, #00b7c5, #0b7c88);
    color: white;
    font-size: 22px;
    padding: 12px 15px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    z-index: 999;
}

.admin-symbol:hover {
    background: linear-gradient(135deg, #0995a4, #0b7c88);
    transform: scale(1.1);
}

    </style>
  <meta charset="UTF-8">
</head>
<body>
<a href="admin.php" class="admin-symbol" title="Admin Panel">⚙️</a>

    <h2>Admin Chat Panel</h2>
    
    <div class="admin-container">
        <!-- User List Sidebar -->
        <div class="user-sidebar">
            <h3>Users</h3>
            <div class="user-list">
                <?php if (count($userList) > 0): ?>
                    <?php foreach ($userList as $user): ?>
                        <a href="?user=<?php echo urlencode($user); ?>" class="user-option <?php if ($user == $selectedUser) echo 'active'; ?>">
                            <?php echo htmlspecialchars($user); ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No users have messaged yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Chat Area -->
        <div class="chat-area">
            <?php if ($selectedUser): ?>
                <div class="chat-header">
                    <h3>Chat with <?php echo htmlspecialchars($selectedUser); ?></h3>
                </div>
                
                <div class="chat-box">
                    <?php foreach ($chatHistory as $chat): ?>
                        <div class="chat-message <?php echo ($chat['sender'] == 'admin') ? 'admin-message' : 'user-message'; ?>">
                            <strong><?php echo htmlspecialchars($chat['sender']); ?>:</strong>
                            <?php echo nl2br(htmlspecialchars($chat['message'])); ?>
                            <em><?php echo $chat['time']; ?></em>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <form method="POST" action="" class="reply-form">
                    <input type="hidden" name="receiver" value="<?php echo htmlspecialchars($selectedUser); ?>">
                    <textarea name="reply" placeholder="Type your reply..." required></textarea>
                    <br>
                    <button type="submit">Send Reply</button>
                </form>
            <?php else: ?>
                <div class="no-users">
                    <p>No users have messaged the admin yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom of chat
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.querySelector('.chat-box');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>