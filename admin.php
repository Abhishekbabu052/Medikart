<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: #f4f9f9;
  display: flex;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  width: 220px;
  background: #00796b;
  color: white;
  display: flex;
  flex-direction: column;
  padding-top: 2rem;
  position: fixed;
  height: 100%;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 2rem;
}

.sidebar a {
  text-decoration: none;
  color: white;
  padding: 1rem 2rem;
  display: block;
  transition: background 0.3s ease;
}

.sidebar a:hover,
.sidebar a.active {
  background-color: #00bfa5;
}

/* Notification Button inside Sidebar */
.notification-btn {
  position: absolute;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  background: #0b7c88;
  color: white;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  transition: all 0.3s ease;
}

.notification-btn:hover {
  background: #0995a4;
  transform: translateX(-50%) scale(1.05);
}

.notification-btn i {
  font-size: 24px;
}

.notification-count {
  position: absolute;
  top: -5px;
  right: -5px;
  background-color: #e74c3c;
  color: white;
  border-radius: 50%;
  width: 22px;
  height: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
}

a {
  text-decoration: none;
  color: white;
}

/* Main Content */
.main {
  margin-left: 220px;
  padding: 2rem;
  width: 100%;
}

.section {
  display: none;
}

.section.active {
  display: block;
}

h1,
h2 {
  color: #00796b;
  margin-bottom: 1rem;
}

/* Forms */
form {
  background: #fff;
  padding: 20px;
  margin-bottom: 30px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

input,
textarea,
select {
  width: 100%;
  padding: 8px;
  margin-top: 8px;
  margin-bottom: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

input[type="submit"] {
  background-color: #00796b;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  padding: 12px;
  font-weight: bold;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
  background-color: #00bfa5;
}

/* Medicine Grid */
.medicine-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
  margin-bottom: 50px;
}

@media (max-width: 1024px) {
  .medicine-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .medicine-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .medicine-grid {
    grid-template-columns: 1fr;
  }
}

.medicine-card {
  background: #fff;
  padding: 20px;
  border-left: 5px solid #00796b;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.medicine-card img {
  max-width: 100px;
  margin-bottom: 15px;
  border-radius: 6px;
  object-fit: cover;
  max-height: 100px;
  width: 100%;
}

.medicine-card strong {
  display: block;
  margin: 4px 0;
  text-align: center;
}

.medicine-card form {
  width: 100%;
  padding: 0;
  box-shadow: none;
  background: transparent;
}

.medicine-card form label {
  margin-top: 8px;
  font-weight: 600;
  display: block;
}

.medicine-card form input[type="text"],
.medicine-card form input[type="number"],
.medicine-card form textarea {
  font-size: 14px;
  resize: vertical;
}

.medicine-card form input[type="submit"] {
  background-color: #00796b;
  padding: 10px;
  font-size: 15px;
  margin-top: 8px;
  border-radius: 6px;
  font-weight: 700;
  transition: background-color 0.3s ease;
}

.medicine-card form input[type="submit"]:hover {
  background-color: #00bfa5;
}

/* Success & Error Messages */
.success {
  color: green;
  font-weight: bold;
  margin-bottom: 20px;
  padding: 10px;
  background-color: #eaffea;
  border-radius: 4px;
  border-left: 4px solid green;
}

.error {
  color: red;
  font-weight: bold;
  margin-bottom: 20px;
  padding: 10px;
  background-color: #ffeaea;
  border-radius: 4px;
  border-left: 4px solid red;
}

/* Delete Link */
a.delete-link {
  color: #e74c3c;
  text-decoration: none;
  font-weight: bold;
  display: inline-block;
  margin-top: 12px;
  transition: color 0.3s ease;
  cursor: pointer;
}

a.delete-link:hover {
  color: #c0392b;
}

/* Container */
.container {
  max-width: 900px;
  margin: 0 auto;
  background: #fff;
  padding: 25px 30px;
  border-radius: 8px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

/* Orders */
.order-card {
  border: 1px solid #ddd;
  background: #fafafa;
  padding: 15px 20px;
  margin-bottom: 20px;
  border-radius: 6px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.order-card strong {
  color: #555;
}

.order-row {
  margin-bottom: 8px;
  font-size: 16px;
  color: #444;
}

hr {
  border: none;
  border-top: 1px solid #eee;
  margin: 30px 0 10px;
}

.no-orders {
  font-size: 18px;
  color: #999;
  text-align: center;
  margin-top: 40px;
}

/* Profile */
.profile-card {
  background: #fff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  max-width: 500px;
}

.profile-card p {
  margin-bottom: 15px;
}

.logout-btn {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  text-decoration: none;
  display: inline-block;
}

.logout-btn:hover {
  background-color: #c0392b;
}

/* Feedback */
.feedback-card {
  background: #fff;
  padding: 20px;
  margin-bottom: 20px;
  border-left: 5px solid #00796b;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}

.feedback-row {
  margin-bottom: 10px;
  font-size: 15px;
}

.feedback-image {
  max-width: 150px;
  margin-top: 10px;
  border-radius: 6px;
}

.no-feedback {
  text-align: center;
  color: #999;
  font-size: 16px;
  margin-top: 20px;
}

/* Notification Panel */
.notification-panel {
  position: fixed;
  bottom: 100px;
  left: 50%;
  transform: translateX(-50%);
  width: 300px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  display: none;
  max-height: 400px;
  overflow-y: auto;
}
  </style>
</head>
<body>
  <?php
  // Database connection is already done via config.php
  $success_message = "";
  $error_message = "";

  // Add medicine
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
      $name = $conn->real_escape_string($_POST["name"]);
      $price = $conn->real_escape_string($_POST["price"]);
      $description = $conn->real_escape_string($_POST["description"]);
      
      // Handle image upload
      $image_path = "";
      if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
          $image_name = $_FILES["image"]["name"];
          $image_tmp = $_FILES["image"]["tmp_name"];
          $upload_dir = "uploads/";
          
          // Create uploads directory if it doesn't exist
          if (!file_exists($upload_dir)) {
              mkdir($upload_dir, 0777, true);
          }
          
          $image_path = $upload_dir . basename($image_name);
          if (move_uploaded_file($image_tmp, $image_path)) {
              $sql = "INSERT INTO medicine (image, name, price, description) 
                      VALUES ('$image_path', '$name', '$price', '$description')";
              if ($conn->query($sql)) {
                  $success_message = "✅ Medicine added successfully.";
              } else {
                  $error_message = "❌ Error: " . $conn->error;
              }
          } else {
              $error_message = "❌ Image upload failed.";
          }
      } else {
          $error_message = "❌ Please select an image.";
      }
  }

  // Edit medicine
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
      $id = intval($_POST["id"]);
      $name = $conn->real_escape_string($_POST["name"]);
      $price = $conn->real_escape_string($_POST["price"]);
      $description = $conn->real_escape_string($_POST["description"]);

      $sql = "UPDATE medicine SET name='$name', price='$price', description='$description' WHERE id=$id";
      if ($conn->query($sql)) {
          $success_message = "✏️ Medicine updated successfully.";
      } else {
          $error_message = "❌ Error: " . $conn->error;
      }
  }

  // Delete medicine
  if (isset($_GET["delete"])) {
      $id = intval($_GET["delete"]);
      if ($conn->query("DELETE FROM medicine WHERE id=$id")) {
          $success_message = "🗑️ Medicine deleted successfully.";
      } else {
          $error_message = "❌ Error deleting medicine: " . $conn->error;
      }
  }
  ?>

  <div class="sidebar">
    <h2 style="color:white;">ADMIN</h2>
    <a href="#" class="active" onclick="showSection('medicines')">Medicines</a>
    <a href="#" onclick="showSection('orders')">Orders</a>
    <a href="#" onclick="showSection('feedback')">Feedback</a>
    <a href="#" onclick="showSection('profile')">Profile</a>
    
    <!-- Notification Button -->
    <div class="notification-btn" onclick="toggleNotifications()">
      <a href="adminchat.php"><i class="fas fa-bell"></i></a>
    </div>
  </div>

  <div class="main">
    <!-- Display success/error messages -->
    <?php if (!empty($success_message)): ?>
      <div class="success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
      <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Medicines Section -->
    <div id="medicines" class="section active">
      <h2>Add Medicine</h2>
      <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Price (₹):</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <input type="submit" name="add" value="Add Medicine">
      </form>

      <h2>Medicine List</h2>
      <div class="medicine-grid">
        <?php
        $medResult = $conn->query("SELECT * FROM medicine");
        if ($medResult->num_rows > 0) {
            while ($row = $medResult->fetch_assoc()) {
                echo "<div class='medicine-card'>";
                echo "<img src='" . $row["image"] . "' alt='Medicine Image'><br>";
                echo "<strong>Name:</strong> " . htmlspecialchars($row["name"]) . "<br>";
                echo "<strong>Price:</strong> ₹" . htmlspecialchars($row["price"]) . "<br>";
                echo "<strong>Description:</strong> " . htmlspecialchars($row["description"]) . "<br><br>";

                echo "<form method='POST'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<label>Edit Name:</label><input type='text' name='name' value='" . htmlspecialchars($row["name"]) . "' required>";
                echo "<label>Edit Price:</label><input type='number' step='0.01' name='price' value='" . htmlspecialchars($row["price"]) . "' required>";
                echo "<label>Edit Description:</label><textarea name='description' required>" . htmlspecialchars($row["description"]) . "</textarea>";
                echo "<input type='submit' name='edit' value='Update'>";
                echo "</form>";

                echo "<a class='delete-link' href='?delete=" . $row["id"] . "' onclick='return confirm(\"Delete this medicine?\")'>Delete</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No medicines found.</p>";
        }
        ?>
      </div>
    </div>

    <!-- Orders Section -->
   <div id="orders" class="section">
  <h2>Order List</h2>
  <?php
  $orderResult = $conn->query("
    SELECT * FROM orders
    ORDER BY id DESC
  ");

  if ($orderResult->num_rows > 0) {
      while ($row = $orderResult->fetch_assoc()) {
          echo "<div class='order-card'>";
          echo "<div class='order-row'><strong>Order ID:</strong> " . htmlspecialchars($row["id"]) . "</div>";

          echo "<div class='order-row'><strong>Customer Name:</strong> " . htmlspecialchars($row["user"]) . "</div>";
	  echo "<div class='order-row'><strong>Product Name:</strong> " . htmlspecialchars($row["name"]) . "</div>";
          echo "<div class='order-row'><strong>Address:</strong> " . nl2br(htmlspecialchars($row["address"])) . "</div>";
          echo "<div class='order-row'><strong>Phone:</strong> " . htmlspecialchars($row["phone"]) . "</div>";
          echo "<div class='order-row'><strong>Pincode:</strong> " . htmlspecialchars($row["pincode"]) . "</div>";
          echo "<div class='order-row'><strong>Price:</strong> ₹" . htmlspecialchars($row["price"]) . "</div>";
          echo "<div class='order-row'><strong>Order Date:</strong> " . htmlspecialchars($row["order_date"]) . "</div>";
          echo "</div>";
      }
  } else {
      echo "<p class='no-orders'>No orders found.</p>";
  }
  ?>
</div>
<!-- Feedback Section -->
<div id="feedback" class="section">
  <h2>Submitted Feedback</h2>

  <?php
  $feedbackResult = $conn->query("
      SELECT feedback.*, users.username 
      FROM feedback 
      LEFT JOIN users ON feedback.user_id = users.id 
      ORDER BY feedback.id DESC
  ");
  ?>

  <?php
  if ($feedbackResult->num_rows == 0) {
      echo "<p class='no-feedback'>No feedback found.</p>";
  } else {
      while ($row = $feedbackResult->fetch_assoc()) {
          echo "<div class='feedback-card'>";
          echo "<div class='feedback-row'><strong>ID:</strong> " . $row['id'] . "</div>";
          echo "<div class='feedback-row'><strong>User:</strong> " . htmlspecialchars(isset($row['username']) ? $row['username'] : 'Unknown') . "</div>";
          echo "<div class='feedback-row'><strong>Subject:</strong> " . htmlspecialchars($row['subject']) . "</div>";
          echo "<div class='feedback-row'><strong>Message:</strong><br>" . nl2br(htmlspecialchars($row['message'])) . "</div>";
          echo "<div class='feedback-row'><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</div>";
          echo "<div class='feedback-row'><strong>Submitted At:</strong> " . $row['submitted_at'] . "</div>";

          if (!empty($row['image_path'])) {
              echo "<div class='feedback-row'><strong>Image:</strong><br><img src='" . htmlspecialchars($row['image_path']) . "' class='feedback-image'></div>";
          } else {
              echo "<div class='feedback-row'><strong>Image:</strong> None</div>";
          }

          echo "</div>";
      }
  }
  ?>
</div>

<!-- Profile Section --> <div id="profile" class="section"> <h1>Admin Profile</h1> <div class="profile-card"> <p>Welcome, Admin!</p> <p>You have full access to manage medicines and view orders.</p> <p><a href="?logout=true" class="logout-btn">Logout</a></p> </div> </div> </div>

  <script>
    function showSection(id) {
      document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
      document.querySelectorAll('.sidebar a').forEach(link => link.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      event.target.classList.add('active');
    }

    // Handle delete confirmation
    document.querySelectorAll('a.delete-link').forEach(link => {
      link.addEventListener('click', function(event) {
        if (!confirm('Are you sure you want to delete this medicine?')) {
          event.preventDefault();
        }
      });
    });

 
 
  </script>
</body>
</html>
<?php
$conn->close();
?>