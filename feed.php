<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $subject = $_POST["subject"];
    $content = $_POST["message"];
    $image_path = null;

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $filename = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            $message = "❌ Image upload failed.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, subject, message, image_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $subject, $content, $image_path);
    if ($stmt->execute()) {
        $message = "✅ Feedback submitted successfully.";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Submit Feedback</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0fdfd;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px 20px;
      color: #333;
    }

    h2 {
      color: #00796b;
      font-size: 28px;
      margin-bottom: 20px;
      text-align: center;
    }

    p {
      text-align: center;
      font-weight: 600;
      margin-bottom: 15px;
    }

    form {
      background: #fff;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
    }

    label {
      font-weight: 600;
      display: block;
      margin: 10px 0 5px;
      color: #004d40;
    }

    input[type="text"], textarea, input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 2px solid #00bfa5;
      border-radius: 8px;
      font-size: 14px;
      outline: none;
      transition: 0.3s;
      box-sizing: border-box;
    }

    input[type="text"]:focus, textarea:focus {
      border-color: #00796b;
      box-shadow: 0 0 6px rgba(0,191,165,0.3);
    }

    textarea {
      resize: none;
      height: 100px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #00bfa5, #00796b);
      color: white;
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
      font-weight: 600;
      text-align: center;
      margin-bottom: 15px;
    }

    .message:contains("✅") { color: green; }
    .message:contains("❌") { color: red; }

    @media (max-width: 480px) {
      body {
        padding: 20px 10px;
      }
      h2 {
        font-size: 24px;
      }
      form {
        padding: 20px;
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

  </style>
<meta charset="UTF-8">

</head>
<body>
  <a href="index.php" class="home-symbol" title="Go Home">🏠</a>
<h2>Feedback / Complaint Form</h2>

<?php if ($message) echo "<p class='message'>$message</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Subject:</label>
    <input type="text" name="subject" required>

    <label>Message:</label>
    <textarea name="message" required></textarea>

    <label>Attach Image (optional):</label>
    <input type="file" name="image" accept="image/*">

    <input type="submit" value="Submit Feedback">
</form>

</body>
</html>
