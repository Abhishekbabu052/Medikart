<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    die("Medicine ID missing.");
}

$medicine_id = intval($_GET["id"]);
$medicine = $conn->query("SELECT * FROM medicine WHERE id=$medicine_id")->fetch_assoc();
$message = "";
$med=$medicine["name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $pincode = $_POST["pincode"];
    $price = $_POST["price"];
    $payment_method = $_POST["payment_method"]; // Remove this line
    $user_id = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;

    // Remove payment_method from the SQL query
    $sql = "INSERT INTO orders (user_id, name, price, address, phone, pincode, user)
            VALUES ($user_id, '$med', '$price', '$address', '$phone', '$pincode', '$name')";

    if ($conn->query($sql) === TRUE) {
        $message = "✅ Order placed successfully.";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Order Medicine</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f5fdfd;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    h2, h3, p {
      margin: 0 0 10px 0;
      text-align: center;
    }

    .medicine-name {
      font-size: 24px;
      font-weight: 700;
      color: #004d40;
      margin-bottom: 5px;
    }

    .medicine-description {
      font-size: 16px;
      color: #555;
      font-style: italic;
      margin-bottom: 8px;
    }

    .medicine-price {
      font-size: 20px;
      font-weight: 600;
      color: #009688;
      margin-bottom: 15px;
    }

    img {
      display: block;
      margin: 15px auto;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    form {
      max-width: 500px;
      margin: 20px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }

    label {
      font-weight: 600;
      display: block;
      margin: 10px 0 5px;
    }

    input[type="text"], textarea, select {
      width: 100%;
      padding: 10px;
      border: 2px solid #00bfa5;
      border-radius: 10px;
      font-size: 14px;
      outline: none;
      transition: 0.3s;
    }

    input[type="text"]:focus, textarea:focus, select:focus {
      border-color: #00796b;
      box-shadow: 0 0 6px rgba(0,191,165,0.3);
    }

    textarea {
      resize: none;
      height: 80px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #00bfa5, #00695c);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 15px;
      transition: 0.3s ease;
    }

    input[type="submit"]:hover {
      transform: scale(1.03);
      box-shadow: 0 6px 20px rgba(0,191,165,0.3);
    }

    .message {
      text-align: center;
      font-weight: 600;
      margin: 12px 0;
    }
    
    .payment-option {
      margin: 15px 0;
      padding: 15px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
    }
    
    .payment-option.selected {
      border-color: #00bfa5;
      background-color: #f0fffd;
    }
    
    .payment-option input[type="radio"] {
      margin-right: 10px;
    }
    
    .card-details {
      display: none;
      margin-top: 15px;
      padding: 15px;
      background: #f9f9f9;
      border-radius: 8px;
      border-left: 4px solid #00bfa5;
    }
    
    .card-details.show {
      display: block;
    }
    
    .card-row {
      display: flex;
      gap: 15px;
      margin-bottom: 10px;
    }
    
    .card-row div {
      flex: 1;
    }.medicine-description {
    font-size: 16px;
    color: #555;
    font-style: italic;
    margin-bottom: 8px;
    /* New styles for better appearance */
    line-height: 1.6;
    padding: 12px 20px;
    background: linear-gradient(135deg, #f8ffff 0%, #f0fafa 100%);
    border-left: 4px solid #00bfa5;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 191, 165, 0.1);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.medicine-description::before {
    content: "💊";
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    opacity: 0.3;
}

/* Alternative style options - choose one */

/* Style 2: Modern card look */
.medicine-description.style2 {
    background: #ffffff;
    border: 1px solid #e0f2f1;
    border-radius: 12px;
    padding: 15px 25px;
    font-style: normal;
    font-weight: 500;
    color: #37474f;
    box-shadow: 0 4px 12px rgba(0, 150, 136, 0.08);
}

/* Style 3: Highlighted badge */
.medicine-description.style3 {
    background: linear-gradient(45deg, #00bfa5, #009688);
    color: white;
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 600;
    font-style: normal;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    border: none;
    box-shadow: 0 4px 15px rgba(0, 191, 165, 0.3);
}

/* Style 4: Simple elegant */
.medicine-description.style4 {
    background: transparent;
    border: 2px dashed #00bfa5;
    color: #00695c;
    font-weight: 500;
    padding: 12px;
    border-radius: 10px;
    font-style: normal;
    box-shadow: none;
}

/* Style 5: With icon background */
.medicine-description.style5 {
    background: #f1f8e9;
    color: #33691e;
    border-left: 4px solid #8bc34a;
    padding: 15px 20px 15px 50px;
    position: relative;
    font-style: normal;
}

.medicine-description.style5::before {
    content: "💡";
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 24px;
    opacity: 0.7;
}

/* Hover effects */
.medicine-description:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 191, 165, 0.2);
}

/* Responsive */
@media (max-width: 600px) {
    .medicine-description {
        padding: 10px 15px;
        font-size: 14px;
        margin-left: 10px;
        margin-right: 10px;
    }
    
    .medicine-description::before {
        display: none; /* Hide icon on mobile */
    }
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
	.back-arrow {
    position: fixed;
    top: 20px;
    left: 20px;
    text-decoration: none;
    background: linear-gradient(135deg, #0995a4, #0b7c88);
    color: white;
    font-size: 24px;
    padding: 10px 15px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    z-index: 1000;
}

.back-arrow:hover {
    transform: scale(1.1);
    background: linear-gradient(135deg, #0bbfa3, #087f7e);
}


  </style>
</head>
<body>
  <a href="index.php" class="home-symbol" title="Go Home">🏠</a>
  <a href="medicines.php" class="back-arrow" title="Go Back">←</a>

<h2 class="medicine-name">Order: <?php echo $medicine["name"]; ?></h2>
<p class="medicine-description"><?php echo $medicine["description"]; ?></p>
<p class="medicine-price">Price: ₹<?php echo $medicine["price"]; ?></p>
<img src="<?php echo $medicine["image"]; ?>" width="120"><br><br>

<?php echo $message ? "<p class='message'>$message</p>" : ""; ?>

<form method="POST" id="orderForm" onsubmit="return validateForm()">
    <input type="hidden" name="price" value="<?php echo $medicine['price']; ?>">

    <label>Your Name:</label><br>
    <input type="text" name="name" required pattern="[A-Za-z\s]+" title="Only letters are allowed"><br>

<label>Address:</label><br>
<textarea name="address" id="address" required oninput="validateAddress(this)"></textarea><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" id="phone" required pattern="[0-9]{10}" title="Phone must be 10 digits"><br>

    <label>Pincode:</label><br>
    <input type="text" name="pincode" id="pincode" required pattern="[0-9]{6}" title="Pincode must be 6 digits"><br>
    
    <label>Payment Method:</label><br>
    
    <div class="payment-option" onclick="selectPayment('cod')">
        <input type="radio" id="cod" name="payment_method" value="Cash on Delivery" checked>
        <label for="cod" style="display: inline; cursor: pointer;">Cash on Delivery</label>
    </div>
    
    <div class="payment-option" onclick="selectPayment('card')">
        <input type="radio" id="card" name="payment_method" value="Card Payment">
        <label for="card" style="display: inline; cursor: pointer;">Card Payment</label>
    </div>
    
    <div id="cardDetails" class="card-details">
        <label>Card Number:</label>
        <input type="text" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
        
        <div class="card-row">
            <div>
                <label>Expiry Date:</label>
                <input type="text" name="expiry_date" placeholder="MM/YY" maxlength="5">
            </div>
            <div>
                <label>CVV:</label>
                <input type="text" name="cvv" placeholder="123" maxlength="3">
            </div>
        </div>
        
        <label>Cardholder Name:</label>
        <input type="text" name="cardholder_name" placeholder="John Doe">
    </div>
    
    <input type="submit" value="Place Order">
</form>
<script>
function selectPayment(method) {
    // Update radio buttons
    document.getElementById(method).checked = true;
    
    // Update visual selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.classList.remove('selected');
    });
    event.currentTarget.classList.add('selected');
    
    // Show/hide card details
    const cardDetails = document.getElementById('cardDetails');
    if (method === 'card') {
        cardDetails.classList.add('show');
        // Make card fields required
        cardDetails.querySelectorAll('input').forEach(input => {
            input.required = true;
        });
    } else {
        cardDetails.classList.remove('show');
        // Remove required from card fields
        cardDetails.querySelectorAll('input').forEach(input => {
            input.required = false;
        });
    }
}

// Initialize payment method selection on page load
document.addEventListener('DOMContentLoaded', function() {
    selectPayment('cod');
    
    // Format card number input
    const cardNumberInput = document.querySelector('input[name="card_number"]');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = formattedValue;
        });
    }
    
    // Format expiry date input
    const expiryInput = document.querySelector('input[name="expiry_date"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            e.target.value = value.substring(0, 5);
        });
    }
    
    // Restrict CVV to numbers only
    const cvvInput = document.querySelector('input[name="cvv"]');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    }
});

function validateForm() {
    const name = document.querySelector("input[name='name']").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const pincode = document.getElementById("pincode").value.trim();
    const paymentMethod = document.querySelector("input[name='payment_method']:checked").value;

    // Name: only letters & spaces
    if (!/^[A-Za-z\s]+$/.test(name)) {
        alert("Name can only contain letters and spaces.");
        return false;
    }

    // Phone: must be exactly 10 digits
    if (!/^\d{10}$/.test(phone)) {
        alert("Phone number must be exactly 10 digits.");
        return false;
    }

    // Pincode: must be exactly 6 digits
    if (!/^\d{6}$/.test(pincode)) {
        alert("Pincode must be exactly 6 digits.");
        return false;
    }

    // ✅ CARD PAYMENT VALIDATION
    if (paymentMethod === "Card Payment") {
        const cardNumber = document.querySelector("input[name='card_number']").value.replace(/\s+/g, '');
        const expiryDate = document.querySelector("input[name='expiry_date']").value.trim();
        const cvv = document.querySelector("input[name='cvv']").value.trim();
        const cardholderName = document.querySelector("input[name='cardholder_name']").value.trim();

        // Card number: must be 16 digits
        if (!/^\d{16}$/.test(cardNumber)) {
            alert("Card number must be exactly 16 digits.");
            return false;
        }

        // Expiry date: must match MM/YY and not be expired
        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate)) {
            alert("Expiry date must be in MM/YY format.");
            return false;
        } else {
            const [month, year] = expiryDate.split("/");
            const now = new Date();
            const inputDate = new Date(`20${year}`, month);
            if (inputDate < now) {
                alert("Your card has expired.");
                return false;
            }
        }

        // CVV: must be 3 digits
        if (!/^\d{3}$/.test(cvv)) {
            alert("CVV must be 3 digits.");
            return false;
        }

        // Cardholder Name: only letters and spaces
        if (!/^[A-Za-z\s]+$/.test(cardholderName)) {
            alert("Cardholder name can only contain letters and spaces.");
            return false;
        }
    }

    return true; // allow submit
}


function validateAddress(textarea) {
    // Allow only letters, commas, dots, and spaces
    const regex = /^[A-Za-z\s,.\-]*$/;
    if (!regex.test(textarea.value)) {
        textarea.value = textarea.value.replace(/[^A-Za-z\s,.\-]/g, '');
    }
}

</script>

</body>
</html>