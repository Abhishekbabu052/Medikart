<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f0fdfd; /* Light Aqua Background */
      color: #333;
    }

    header {
      background: #0b7c88;
      padding: 20px;
      text-align: center;
      color: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    header h1 {
      margin: 0;
      font-size: 2rem;
    }

    .about-section {
      text-align: center;
      padding: 50px 20px;
    }

    .about-section h2 {
      color: #0b7c88;
      font-size: 2rem;
      margin-bottom: 15px;
    }

    .about-section p {
      max-width: 700px;
      margin: 0 auto;
      font-size: 1.1rem;
      line-height: 1.6;
      color: #333;
    }

    .team {
      display: flex;
      justify-content: center;
      gap: 30px;
      padding: 50px 20px;
      flex-wrap: wrap;
    }

    .member {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 280px;
      height: 430px;
      text-align: center;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .member:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }

    .member img {
      width: 100%;
      height: 280px;
      object-fit: cover; /* ensures portrait fit */
      border-bottom: 3px solid #0b7c88;
    }

    .member h3 {
      margin: 15px 0 5px;
      font-size: 1.2rem;
      color: #0b7c88;
    }

    .member p {
      margin: 0;
      font-size: 1rem;
      color: #333;
    }

    footer {
      background: #0b7c88;
      color: #fff;
      text-align: center;
      padding: 15px;
      margin-top: 50px;
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
</head>
<body>
  <a href="index.php" class="home-symbol" title="Go Home">🏠</a>
  <header>
    <h1>About Us</h1>
  </header>

  <section class="about-section">
    <h2>Who We Are</h2>
    <p>
      We are a passionate team dedicated to delivering high-quality solutions with creativity,
      innovation, and teamwork. Our mission is to bring value and satisfaction to our clients
      while continuously striving for excellence.
    </p>
  </section>

  <section class="team">
    <div class="member">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQFpVdqEUvmXdqEQRp2YKJzdJnnSQ51BPDLog&s" alt="Member 1">
      <h3>Abhin K Das</h3>
      <p>Project Manager</p>
    </div>
    <div class="member">
      <img src="https://i.ibb.co/m51dhkYp/Me.jpg" alt="Member 2">
      <h3>Abhishek Babu</h3>
      <p>Lead Developer</p>
    </div>
    <div class="member">
      <img src="https://encrypt" alt="Member 3">
      <h3>Aben Shijo</h3>
      <p>UI/UX Designer</p>
    </div>
  </section>

  <footer>
    <p>© 2025 Your Company. All rights reserved.</p>
  </footer>

</body>
</html>
