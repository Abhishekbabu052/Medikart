<?php
session_start();
$conn = new mysqli("sql306.infinityfree.com", "if0_41589871", "nQ8ghVEvIqd8E2N", "if0_41589871_medikart");

// Handle form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']); // Hash password

    if ($_POST['form_type'] == 'register') {
        $check = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($check->num_rows > 0) {
            $message = "Username already exists.";
            $message_type = "error";
        } else {
            $insert = $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
            $message = $insert ? "Registration successful. Please login." : "Error during registration.";
            $message_type = $insert ? "success" : "error";
        }
    } elseif ($_POST['form_type'] == 'login') {
        // Check if it's admin login
        if ($username === 'admin' && $password === md5('admin123')) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_username'] = 'admin';
            header("Location: admin.php");
            exit();
        }
        
        // Regular user login
        $result = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid username or password.";
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MediKart - Login & Register</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body {
    background:url('https://i.pinimg.com/originals/29/24/62/2924624b72ad602afebc66b4cb862a11.gif')no-repeat center center/cover;
    height: 100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.container {
    background-color: rgba(255,255,255,0.9);
    border-radius: 15px;
    box-shadow:0 15px 30px rgba(0,0,0,0.2);
    width: 400px; max-width:90%;
    padding:30px;
    position:relative;
    overflow:hidden;
}
.container::before {
    content:'';
    position:absolute;
    top:-50%; left:-50%;
    width:200%; height:200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), rgba(255,255,255,0.2));
    transform: rotate(45deg);
    animation: shine 6s infinite;
    z-index:0;
}
@keyframes shine {
    0% { transform: rotate(45deg) translateX(-100%); }
    100% { transform: rotate(45deg) translateX(100%); }
}
.form-container { position:relative; z-index:1; }
h2 { text-align:center; margin-bottom:20px; color:#333; font-size:28px; position:relative; }
h2::after {
    content:'';
    display:block;
    width:60px; height:4px;
    background: linear-gradient(to right, #00bfa5, #00796b);
    margin:8px auto;
    border-radius:2px;
}
.tabs { display:flex; margin-bottom:20px; border-radius:8px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
.tab { flex:1; padding:12px; text-align:center; background:#f1f1f1; cursor:pointer; transition:0.3s; font-weight:600; }
.tab.active { background:#00796b; color:white; }
.form { display:none; animation: fadeIn 0.5s ease forwards; }
.form.active { display:block; }
@keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
.input-group { margin-bottom:20px; position:relative; }
.input-group input {
    width:100%; padding:12px 15px; border:2px solid #ddd; border-radius:8px; font-size:16px; transition:0.3s;
}
.input-group input:focus { border-color:#00796b; box-shadow:0 0 8px rgba(0,121,107,0.3); outline:none; }
.input-group label {
    position:absolute; top:12px; left:15px; color:#999; pointer-events:none; transition:0.3s;
}
.input-group input:focus + label,
.input-group input:not(:placeholder-shown) + label {
    top:-10px; left:10px; font-size:12px; background:#fff; padding:0 5px; color:#00796b;
}
.btn {
    width:100%; padding:12px; background:linear-gradient(to right,#00bfa5,#00796b);
    border:none; border-radius:8px; color:white; font-size:16px; font-weight:600; cursor:pointer;
    transition:0.3s; box-shadow:0 4px 10px rgba(0,121,107,0.3);
}
.btn:hover { transform:translateY(-2px); box-shadow:0 6px 15px rgba(0,121,107,0.5); }
.btn:active { transform:translateY(0); }
.message { margin-top:20px; padding:10px; border-radius:8px; text-align:center; font-weight:500; animation:popIn 0.5s ease forwards; }
@keyframes popIn { 0%{ transform:scale(0.8); opacity:0;} 100%{ transform:scale(1); opacity:1; } }
.success { background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.error { background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
.medicine-icon { text-align:center; margin-bottom:20px; color:#00796b; font-size:36px; }

/* Admin Icon Styles */
.admin-icon {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    z-index: 1000;
    border-radius: 50%;
    transition: 0.2s;
}

.admin-icon:hover {
    transform: scale(1.1);
}

.admin-icon img {
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
</style>
</head>
<body>
<!-- Admin Icon -->
<a href="login.php" class="admin-icon">
    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="50" height="50" alt="Admin Login">
</a>

<div class="container">
    <div class="form-container">
        <div class="medicine-icon">💊</div>
        <h2>MediKart</h2>
        <div class="tabs">
            <div class="tab active" onclick="showForm('login')">Login</div>
            <div class="tab" onclick="showForm('register')">Register</div>
        </div>

        <div id="message">
            <?php if(isset($message)) echo "<div class='message $message_type'>$message</div>"; ?>
        </div>

        <form id="loginForm" class="form active" method="post">
            <input type="hidden" name="form_type" value="login">
            <div class="input-group">
                <input type="text" name="username" placeholder=" " required>
                <label>Username</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder=" " required>
                <label>Password</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <form id="registerForm" class="form" method="post">
    <input type="hidden" name="form_type" value="register">
    
    <div class="input-group">
        <input type="text" 
               name="username" 
               placeholder=" " 
               required 
               pattern="[A-Za-z]+" 
               title="Only letters are allowed (A–Z, a–z)">
        <label>Username</label>
    </div>
    
    <div class="input-group">
        <input type="password" name="password" placeholder=" " required>
        <label>Password</label>
    </div>
    
    <button type="submit" class="btn">Register</button>
</form>

    </div>
</div>

<script>
function showForm(formType) {
    document.querySelectorAll('.tab').forEach(tab=>tab.classList.remove('active'));
    document.querySelectorAll('.form').forEach(form=>form.classList.remove('active'));
    if(formType==='login'){
        document.querySelector('.tab:first-child').classList.add('active');
        document.getElementById('loginForm').classList.add('active');
    } else {
        document.querySelector('.tab:last-child').classList.add('active');
        document.getElementById('registerForm').classList.add('active');
    }
    document.getElementById('message').innerHTML='';
    document.getElementById('message').className='';
}
</script>
</body>
</html>
</html>
