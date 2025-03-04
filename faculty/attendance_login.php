<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Database connection
include 'db_connect.php';

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    $input_hashed_password = hash('sha256', $input_password);

    // Fetch hashed password from the database
    $sql = "SELECT hashed_password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $input_username);
    $stmt->execute();
    $stmt->bind_result($stored_hashed_password);
    $stmt->fetch();
    $stmt->close();

    if ($input_hashed_password === $stored_hashed_password) {
        $_SESSION['logged_in'] = true;
        header('Location: attendance_dashboard.php');  // Redirect to dashboard after login
    } else {
        echo "Invalid username or password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Login</title>
    <style>
    /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    color: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px; /* Adjusted padding for smaller screens */
}

/* Form styling */
form {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slightly lighter shadow */
    text-align: center;
    color: #000;
}

/* Header styling */
h1 {
    margin-bottom: 20px;
    font-size: 1.8em;
    color: #333; /* Slightly darker text for better readability */
}

/* Input styling */
input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd; /* Softer border color */
    border-radius: 4px;
    background-color: #f9f9f9;
    color: #333; /* Slightly darker text for better readability */
    transition: border-color 0.3s;
}

input:focus {
    border-color: #000; /* Black border on focus */
    outline: none;
}

/* Button styling */
button {
    background-color: #000;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background-color 0.3s, color 0.3s, border 0.3s;
}

button:hover {
    background-color: #fff;
    color: #000;
    border: 1px solid #000;
}

</style>
    
</head>
<body>
    <form action="attendance_login.php" method="POST">
        <h1>Attendance Login</h1>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
