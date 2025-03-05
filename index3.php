<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Simulate database connection (replace with your actual connection)
$host = "localhost";
$user = "your_db_username";
$pass = "your_db_password";
$db = "your_db_name";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Database connection failed');</script>");
}

// Process faculty login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Get stored password
    $result = $conn->query("SELECT password FROM users WHERE username = '$username'");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row['password'];
        $input_hash = hash('sha256', $password);
        
        if ($input_hash === $stored_hash) {
            $_SESSION['logged_in'] = true;
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Counsellor's Book</title>
  <style>
    /* Your original CSS remains unchanged */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      color: #333;
    }
    /* ... rest of your CSS ... */
  </style>
</head>
<body>
  <!-- Your original HTML remains unchanged -->
  <div class="header">
    <img src="assets/logo.jpg" alt="Counsellor's Book Image">
  </div>
  <div class="banner">
    <marquee behavior="scroll" direction="left">
      <p>Welcome to the Counsellor's Book System</p>
    </marquee>
  </div>
  <div class="main-container">
    <div class="container">
      <h2>Institution Login</h2>
      <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
    </div>
    <!-- Rest of your HTML -->
  </div>
</body>
</html>
