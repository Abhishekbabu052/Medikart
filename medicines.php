<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

// Fetch medicines
$sql = "SELECT * FROM medicine";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediKart | Medicines</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  font-family: 'Poppins', sans-serif;
  background:url('https://i.ibb.co/0RG1mRTB/me.jpg')no-repeat center center/cover;
  color: #333;
}

header {
  background: linear-gradient(135deg, #00bfa5, #00695c);
  color: white;
  padding: 2rem 1rem;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

header h1 {
  font-size: 2.6rem;
  letter-spacing: 1px;
  font-weight: 600;
}

.search-bar {
  margin: 2rem auto;
  text-align: center;
}

.search-bar input {
  padding: 0.8rem 1.1rem;
  width: 320px;
  max-width: 90%;
  border: 2px solid #00bfa5;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.search-bar input:focus {
  outline: none;
  border-color: #00796b;
  box-shadow: 0 0 6px rgba(0, 191, 165, 0.3);
}

/* ======= Grid for 4 cards per row, full width ======= */
.product-grid {
  display: grid;
  width: 100%;
  grid-template-columns: repeat(4, 1fr);
  gap: 50px;
  padding: 3rem 2rem;
  max-width: 100%; /* full width, no max */
  margin: auto;
  min-height: 480px;
}

@media (max-width: 1024px) {
  .product-grid {
    grid-template-columns: repeat(3, 1fr);
    min-height: 460px;
  }
}

@media (max-width: 768px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
    min-height: 440px;
  }
}

@media (max-width: 480px) {
  .product-grid {
    grid-template-columns: 1fr;
    min-height: 420px;
  }
}

.card {
  background-color: #ffffff;
  border-radius: 18px;
  padding: 16px;
  box-shadow: 0 3px 15px rgba(0, 0, 0, 0.07);
  text-align: center;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow: hidden;
  height: 400px;
  width: 100%; /* fill the grid cell */
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0, 191, 165, 0.2);
}

.card img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 12px;
  transition: transform 0.3s ease;
}

.card:hover img {
  transform: scale(1.05);
}

.card h3 {
  margin-top: 16px;
  font-size: 16px;
  font-weight: 600;
}

.card button {
  margin-top: 12px;
  background: linear-gradient(to right, #00bfa5, #00796b);
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card button:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 18px rgba(0, 191, 165, 0.4);
}

.description {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 65%;
  padding: 18px;
  background: rgba(0, 128, 128, 0.3);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  color: #C9F0DC;
  font-size: 14px;
  text-align: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 15px;
  overflow: auto;
  line-height: 1.6;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
}

.card:hover .description {
  opacity: 1;
}

footer {
  background-color: #0b7c88;
  color: white;
  text-align: center;
  padding: 1rem;
  margin-top: 3rem;
  font-size: 14px;
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
<body>
  <a href="index.php" class="home-symbol" title="Go Home">🏠</a>

  <header>
    <h1>Medicines</h1>
  </header>

  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Search medicines..." onkeyup="filterMedicines()" />
  </div>

  <div class="product-grid">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card' data-aos='fade-up'>";
            echo "<img src='" . $row["image"] . "' alt='Medicine Image'>";
            echo "<h3>" . $row["name"] . "</h3>";
            echo "<div class='description'>" . $row["description"] . "</div>";
            echo "<button onclick=\"location.href='order.php?id=" . $row["id"] . "'\">Buy ₹" . $row["price"] . "</button>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>No medicines found.</p>";
    }

    $conn->close();
    ?>
  </div>

  <footer>
    <p>&copy; 2025 MediKart. All rights reserved.</p>
  </footer>

  <!-- AOS Scripts -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      once: true
    });

    function filterMedicines() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const cards = document.querySelectorAll('.card');

      cards.forEach(card => {
        const name = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = name.includes(input) ? 'block' : 'none';
      });
    }
  </script>

</body>
</html>

