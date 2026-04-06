<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

if (!isset($_SESSION['user_id'])) {
    echo '<div style="text-align: center; padding: 20px;">
            <p>Please log in to view your orders</p>
            <a href="login.php" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background: #0b7c88; color: white; text-decoration: none; border-radius: 4px;">Login</a>
          </div>';
    exit();
}

$user_id = intval($_SESSION['user_id']);
$result = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");

if ($result->num_rows == 0) {
    echo '<p style="text-align: center; padding: 20px;">No orders found.</p>';
} else {
    while ($row = $result->fetch_assoc()) {
        $order_date = date("d M Y", strtotime($row['order_date']));
        $delivery_date = date("d M Y", strtotime($row['order_date'] . " +2 days"));
        
        echo '<div class="order-item">';
        echo '<p><strong>Medicine:</strong> ' . htmlspecialchars($row['name']) . '</p>';
        echo '<p><strong>Price:</strong> ₹' . number_format($row['price'], 2) . '</p>';
        echo '<p><strong>Address:</strong> ' . nl2br(htmlspecialchars($row['address'])) . '</p>';
        echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
        echo '<p><strong>Pincode:</strong> ' . htmlspecialchars($row['pincode']) . '</p>';
        echo '<div class="delivery-info">';
        echo '<p><strong>Order Date:</strong> ' . $order_date . '</p>';
        echo '<p><strong>Expected Delivery:</strong> ' . $delivery_date . '</p>';
        echo '</div>';
        echo '</div>';
    }
}

$conn->close();
?>
