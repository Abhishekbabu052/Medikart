<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle message submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $sender = $_SESSION['username'];
    $receiver = 'admin';
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO replay (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $sender, $receiver, $message);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch chat history using bind_result instead of get_result
$sender = $_SESSION['username'];
$receiver = 'admin';

$stmt = $conn->prepare("SELECT sender, message, sent_at FROM replay WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY sent_at ASC");
$stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($chat_sender, $chat_message, $chat_time);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Chat with Admin</title>
    <style>
        /* Your Teal Color Scheme */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 0;
            background-color: #f0fdfd; /* Light Aqua Background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
        }
        
        h2 {
            color: #0b7c88; /* Main Teal */
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .chat-box { 
            border: 1px solid #ddd; /* Gray Border */
            padding: 20px;
            max-width: 100%;
            margin-bottom: 20px;
            background-color: #ffffff; /* White */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: 400px;
            overflow-y: auto;
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
        
        .user-message {
            background: linear-gradient(135deg, #00b7c5, #00798c); /* Aqua Gradient */
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }
        
        .admin-message {
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
        
        form {
            background-color: #ffffff; /* White */
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
	.home-symbol {
    position: fixed;
    top: 20px;
    right: 20px;
    text-decoration: none;
    background: linear-gradient(135deg, #0995a4, #0b7c88);
    color: white;
    font-size: 22px;
    padding: 12px 15px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    z-index: 1000;
}

.home-symbol:hover {
    transform: scale(1.1);
}

    </style>
<meta charset="UTF-8">
</head>
<body>
  <a href="index.php" class="home-symbol" title="Go Home">🏠</a>
    <div class="container">
        <h2>Chat with Admin</h2>
        
        <div class="chat-box">
            <?php while ($stmt->fetch()): ?>
                <div class="chat-message <?php echo ($chat_sender == $_SESSION['username']) ? 'user-message' : 'admin-message'; ?>">
                    <strong><?php echo htmlspecialchars($chat_sender); ?>:</strong>
                    <?php echo nl2br(htmlspecialchars($chat_message)); ?>
                    <em><?php echo $chat_time; ?></em>
                </div>
            <?php endwhile; ?>
        </div>
        
        <form method="POST" action="">
            <textarea name="message" placeholder="Type your message here..." required></textarea>
            <br>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        // Auto-scroll to bottom of chat
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.querySelector('.chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>