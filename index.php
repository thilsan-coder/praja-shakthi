<?php
include 'db.php';
session_start();
$message = "";

// 1. Logic for Registration
if (isset($_POST['register'])) {
    $user = $_POST['username'];
    $role = $_POST['role'];
    // Password ah secure ah save panna password_hash use pandrom
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, role) VALUES ('$user', '$pass', '$role')";
    if ($conn->query($sql) === TRUE) {
        $message = "<p style='color:green;'>Registration Successful! Please Login.</p>";
    } else {
        $message = "<p style='color:red;'>Error: Username already exists!</p>";
    }
}

// 2. Logic for Login
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$user'");
    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: dashboard.php"); // Login success na dashboard ku pogum
        } else {
            $message = "<p style='color:red;'>Invalid Password!</p>";
        }
    } else {
        $message = "<p style='color:red;'>User not found!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Praja Shakthi - Login/Register</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #1a73e8; margin-bottom: 20px; }
        input, select { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #1a73e8; border: none; color: white; border-radius: 6px; cursor: pointer; font-size: 16px; transition: 0.3s; }
        button:hover { background-color: #1557b0; }
        .toggle-btn { text-align: center; margin-top: 15px; cursor: pointer; color: #1a73e8; font-weight: bold; }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Praja Shakthi</h2>
    <div id="msg-box"><?php echo $message; ?></div>

    <form id="login-form" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <div class="toggle-btn" onclick="toggleForms()">New User? Register here</div>
    </form>

    <form id="register-form" class="hidden" method="POST">
        <input type="text" name="username" placeholder="Choose Username" required>
        <input type="password" name="password" placeholder="Choose Password" required>
        <select name="role">
            <option value="GN_Officer">Grama Niladhari</option>
            <option value="Council_Member">Council Member</option>
            <option value="Community_Member">Community Member</option>
            <option value="Divisional_Secretariat">Divisional Secretariat</option>
        </select>
        <button type="submit" name="register" style="background-color: #34a853;">Register</button>
        <div class="toggle-btn" onclick="toggleForms()">Already have an account? Login</div>
    </form>
</div>

<script>
    function toggleForms() {
        document.getElementById('login-form').classList.toggle('hidden');
        document.getElementById('register-form').classList.toggle('hidden');
        document.getElementById('msg-box').innerHTML = "";
    }
</script>

</body>
</html>