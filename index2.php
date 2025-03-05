<?php
session_start(); // Start the session
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'faculty/db_connect.php'; // Ensure this file correctly connects to the database

$error_message = ""; // Default empty error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Fetch hashed password from the database
    $sql = "SELECT hashed_password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $input_username);
    $stmt->execute();
    $stmt->bind_result($stored_hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the password
    if ($stored_hashed_password && password_verify($input_password, $stored_hashed_password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $input_username;
        header('Location: home.php'); // Redirect to home page on success
        exit();
    } else {
        $error_message = "Invalid username or password!";
    }

    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Counsellor's Book</title>
  <style>
    /* Your CSS styles here */
  </style>
</head>
<body>
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
      <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
    </div>
    <div class="container">
      <h2>Student Login</h2>
      <form action="student/parent111.php" method="POST">
        <input type="text" name="roll_no" placeholder="Roll Number" required>
        <input type="text" name="dob" placeholder="Date of Birth (DD/MM/YYYY)">
        <button type="submit">Login</button>
      </form>
    </div>
    <div class="notice_board">
      <h2>Notice Board</h2>
      <marquee behavior="scroll" direction="left">
        <p style="color: red; font-weight:600;">Important: Faculty and Student Login Details are available on the portal.</p>
        <p style="color: red; font-weight:600;">Note: The system will be down for maintenance from 2:00 AM to 4:00 AM tomorrow.</p>
        <p style="color: red; font-weight:600;">Reminder: Mark your attendance before the deadline to avoid penalties.</p>
      </marquee>
    </div>
  </div>

  <script>
    // Show alert message if PHP sends an error
    <?php if (!empty($error_message)): ?>
      setTimeout(() => {
        alert("<?php echo $error_message; ?>");
      }, 500); // Show alert after half a second
    <?php endif; ?>
  </script>
</body>
</html>
