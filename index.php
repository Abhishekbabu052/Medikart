<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediKart HOME - Online Medical Store</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

  <!-- AOS Animation -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

  <!-- FontAwesome for social icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      font-family: 'Poppins', sans-serif;
      width: 100%;
      overflow-x: hidden;
      background-color: #f0fdfd;
      scroll-behavior: smooth;
    }
    /* Header */
    header {
      background-color: #0b7c88;
      padding: 1rem 2rem;
      color: white;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .header-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1200px;
      margin: 0 auto;
    }
    .logo span {
      font-size: 2rem;
      font-weight: 600;
      animation: slideIn 1s ease-out forwards;
    }
    .welcome-container {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .welcome-message {
      font-size: 1.1rem;
      color: white;
    }
    .logout-btn {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s;
      font-family: 'Poppins', sans-serif;
      text-decoration: none;
      display: inline-block;
    }
    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.3);
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(-50px); }
      to { opacity: 1; transform: translateX(0); }
    }
    /* Floating Admin Icon */
    .admin-icon {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 999;
      background: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    .admin-icon img {
      width: 30px;
      height: 30px;
      transition: transform 0.3s ease;
    }
    .admin-icon:hover img {
      transform: scale(1.1);
    }
    /* Orders Button */
    .orders-btn {
      position: fixed;
      bottom: 100px;
      right: 30px;
      background: #0b7c88;
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      z-index: 900;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .orders-btn:hover {
      background: #0995a4;
      transform: scale(1.05);
    }
    .orders-btn i {
      font-size: 24px;
    }
    /* Orders Panel */
    .orders-panel {
      position: fixed;
      top: 0;
      right: -400px;
      width: 380px;
      height: 100vh;
      background: white;
      box-shadow: -5px 0 15px rgba(0,0,0,0.1);
      z-index: 999;
      transition: right 0.4s ease;
      overflow-y: auto;
      padding: 20px;
    }
    .orders-panel.active {
      right: 0;
    }
    .orders-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }
    .close-panel {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #0b7c88;
    }
    .order-item {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      background: #f9f9f9;
    }
    .delivery-info {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px dashed #ccc;
      color: #0b7c88;
      font-weight: 600;
    }
    /* Feedback Button */
    .feedback-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #0b7c88;
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      z-index: 900;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .feedback-btn:hover {
      background: #0995a4;
      transform: scale(1.05);
    }
    .feedback-btn i {
      font-size: 24px;
    }
    /* Banner */
    .banner {
      height: 100vh;
      background: linear-gradient(rgba(11, 124, 136, 0.6), rgba(0, 0, 0, 0.6)),
        url('https://images.unsplash.com/photo-1588776814546-ec7c2f1da46f?auto=format&fit=crop&w=1500&q=80')
        no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
      padding: 0 1rem;
      position: relative;
    }
    .banner-overlay {
      max-width: 800px;
    }
    .banner-overlay h1 {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      animation: fadeInDown 1s ease forwards;
    }
    .banner-overlay p {
      font-size: 1.5rem;
      margin-bottom: 2rem;
      animation: fadeInUp 1.2s ease forwards;
    }
    .banner button {
      margin-top: 1rem;
      padding: 1rem 2.5rem;
      background: linear-gradient(135deg, #00b7c5, #00798c);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.3s ease;
    }
    .banner button:hover {
      background: linear-gradient(135deg, #00798c, #00b7c5);
      transform: scale(1.05);
    }
    @keyframes fadeInDown {
      0% { opacity: 0; transform: translateY(-30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    /* About Section */
    .about-section {
      padding: 5rem 2rem;
      background-color: #ffffff;
      max-width: 900px;
      margin: 0 auto;
      text-align: center;
      color: #0b7c88;
    }
    .about-section h2 {
      font-size: 2.5rem;
      margin-bottom: 2rem;
    }
    .about-section p {
      font-size: 1.2rem;
      line-height: 1.8;
      color: #333;
      max-width: 800px;
      margin: 0 auto;
    }
    /* Contact Section */
    .contact-section {
      padding: 5rem 2rem;
      background-color: #eef9fa;
      text-align: center;
    }
    .contact-container {
      max-width: 900px;
      margin: 0 auto;
    }
    .contact-icon {
      width: 60px;
      margin-bottom: 1.5rem;
    }
    .contact-section h2 {
      font-size: 2.5rem;
      margin-bottom: 2rem;
      color: #0b7c88;
    }
    .contact-section p {
      margin: 0.8rem 0;
      font-size: 1.1rem;
    }
    /* Footer */
    footer {
      background-color: #0b7c88;
      color: white;
      text-align: center;
      padding: 2rem 1rem;
    }
    .social-icons {
      margin: 1rem 0 1.5rem 0;
    }
    .social-icons a {
      color: white;
      margin: 0 15px;
      font-size: 1.8rem;
      transition: color 0.3s ease;
    }
    .social-icons a:hover {
      color: #a6e5eb;
      transform: translateY(-3px);
    }
    /* Responsive Design */
    @media (max-width: 768px) {
      .header-container {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }
      .welcome-container {
        flex-direction: column;
        gap: 10px;
      }
      .banner-overlay h1 {
        font-size: 2.5rem;
      }
      .banner-overlay p {
        font-size: 1.2rem;
      }
      .about-section, .contact-container {
        padding: 3rem 1.5rem;
      }
      .about-section h2, .contact-section h2 {
        font-size: 2rem;
      }
      .orders-panel {
        width: 100%;
        right: -100%;
      }
    }
    @media (max-width: 480px) {
      header {
        padding: 1rem;
      }
      .logo span {
        font-size: 1.8rem;
      }
      .banner-overlay h1 {
        font-size: 2rem;
      }
      .banner-overlay p {
        font-size: 1rem;
      }
      .banner button {
        padding: 0.8rem 1.8rem;
        font-size: 1rem;
      }
      .admin-icon {
        top: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
      }
      .admin-icon img {
        width: 24px;
        height: 24px;
      }
      .orders-btn, .feedback-btn {
        width: 50px;
        height: 50px;
        right: 20px;
      }
      .orders-btn {
        bottom: 90px;
      }
    }
	/* Notification Button */
.notification-btn {
  position: fixed;
  bottom: 30px;
  left: 30px;
  background: #0b7c88;
  color: white;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  z-index: 900;
  cursor: pointer;
  transition: all 0.3s ease;
}
.notification-btn:hover {
  background: #0995a4;
  transform: scale(1.05);
}
.notification-btn i {
  font-size: 24px;
}
a{text-decoration:none;color:white;}
.ab{text-decoration:none;color:#0b7c88;border: 4px solid #0b7c88;}
</style>
</head>
<body>

  <!-- Floating Admin Icon -->
  <div class="admin-icon">
    <a href="login.php"><img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin" title="Admin Panel" /></a>
  </div>
 
  <!-- Notification Button -->
<?php if (isset($_SESSION['user_id'])): ?>
<div class="notification-btn" title="Notifications">
 <a href="userchat.php"> <i class="fas fa-bell"></i></a>
</div>
<?php endif; ?>

  <!-- Orders Button -->
<?php if (isset($_SESSION['user_id'])): ?>
  <div class="orders-btn" title="My Orders">
    <i class="fas fa-shopping-bag"></i>
  </div>
<?php endif; ?>

  <!-- Feedback Button -->
<?php if (isset($_SESSION['user_id'])): ?>
  <a href="feed.php" class="feedback-btn" title="Give Feedback">
    <i class="fas fa-comment-dots"></i>
  </a>
<?php endif; ?>

  <!-- Orders Panel -->
<?php if (isset($_SESSION['user_id'])): ?>
  <div class="orders-panel">
    <div class="orders-header">
      <h2>My Orders</h2>
      <button class="close-panel">&times;</button>
    </div>
    <div id="orders-content">
      <!-- Orders will be loaded here via PHP/JavaScript -->
    </div>
  </div>
<?php endif; ?>

  <!-- Header -->
  <header>
    <div class="header-container">
      <div class="logo">
        <span>MediKart</span>
      </div>
      <div class="welcome-container">
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
          <a href="?logout=true" class="logout-btn">Logout</a>
        <?php else: ?>
          <a href="login.php" class="logout-btn">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Banner -->
  <section class="banner">
    <div class="banner-overlay">
      <h1>Welcome to MediKart</h1>
      <p>Your trusted source for medicines and healthcare</p>
      <button><a href="medicines.php" style="text-decoration:none;color:white;">Shop Now</a></button>
    </div>
  </section>

  <!-- About Us Section -->
  <section class="about-section" data-aos="fade-up">
    <h2><a class="ab"href="about.php">About Us</a></h2>
    <p>
      MediKart is dedicated to providing quality medicines and healthcare products online.
      We strive to make healthcare accessible and affordable for everyone. Our platform offers
      a wide range of products sourced from trusted manufacturers to ensure safety and efficacy.
      Customer satisfaction and well-being are our top priorities.
    </p>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="contact-section">
    <div class="contact-container">
      <h2 data-aos="fade-up">Contact Us</h2>
      <img src="https://cdn-icons-png.flaticon.com/512/597/597177.png" alt="Contact Icon" class="contact-icon" />
      <p>Email: support@medikart.com</p>
      <p>Phone: +1 234 567 890</p>
      <p>Address: 123 Health Street, Wellness City</p>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="social-icons">
      <a href="https://in.linkedin.com/in/abhin-k-das-7655b8379" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
      <a href="https://www.instagram.com/accounts/login/?next=%2Fjimbru_02_%2F&source=omni_redirect" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      <a href="https://in.linkedin.com/in/aben-shijo-4205192a7" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
    </div>
    <p>&copy; 2025 MediKart. All rights reserved.</p>
  </footer>

  <!-- AOS Script -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    // Initialize AOS
    AOS.init({ duration: 800, once: true });
    
    <?php if (isset($_SESSION['user_id'])): ?>
    // Orders panel functionality
    document.addEventListener('DOMContentLoaded', function() {
      const ordersBtn = document.querySelector('.orders-btn');
      const ordersPanel = document.querySelector('.orders-panel');
      const closePanel = document.querySelector('.close-panel');
      
      // Toggle orders panel
      ordersBtn.addEventListener('click', function() {
        ordersPanel.classList.toggle('active');
        loadOrders();
      });
      
      // Close panel
      closePanel.addEventListener('click', function() {
        ordersPanel.classList.remove('active');
      });
      
      // Close panel when clicking outside
      document.addEventListener('click', function(event) {
        if (!ordersPanel.contains(event.target) && 
            !ordersBtn.contains(event.target) && 
            ordersPanel.classList.contains('active')) {
          ordersPanel.classList.remove('active');
        }
      });
      
      // Load orders via AJAX
      function loadOrders() {
        const ordersContent = document.getElementById('orders-content');
        ordersContent.innerHTML = '<p style="text-align: center; padding: 20px;">Loading orders...</p>';
        
        // Make AJAX request to fetch orders
        fetch('get_orders.php')
          .then(response => response.text())
          .then(data => {
            ordersContent.innerHTML = data;
          })
          .catch(error => {
            ordersContent.innerHTML = '<p style="text-align: center; padding: 20px; color: red;">Error loading orders. Please try again.</p>';
            console.error('Error:', error);
          });
      }
    });
    <?php endif; ?>
  </script>

</body>
</html>