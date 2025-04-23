<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$show_alert = false; // Flag to control alert display
$config = include('config.php'); // Include the config.php file

// Handle Institution Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    include 'faculty/db_connect.php';

    // Verify hCaptcha
    $hcaptchaResponse = $_POST['h-captcha-response'];
    $secretKey = $config['HCAPTCHA_SECRET_KEY'];

    $verifyUrl = 'https://hcaptcha.com/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $hcaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ]
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($verifyUrl, false, $context);
    $resultJson = json_decode($result, true);

    if ($resultJson['success'] !== true) {
        $error = 'hCaptcha verification failed. Please try again.';
    } else {
        // Sanitize user inputs
        $input_username = htmlspecialchars($_POST['username']);
        $input_password = htmlspecialchars($_POST['password']);
        $input_hashed_password = hash('sha256', $input_password);

        // Prepare and execute SQL statement securely
        $sql = "SELECT hashed_password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $input_username);
            $stmt->execute();
            $stmt->bind_result($stored_hashed_password);
            $stmt->fetch();
            
            // Verify password
            if ($input_hashed_password === $stored_hashed_password) {
                $_SESSION['logged_in'] = true;
                $stmt->close();
                $conn->close();
                header('Location: faculty/home.php');
                exit();
            } else {
                $show_alert = true; // Set flag for invalid credentials
            }
            
            $stmt->close();
        } else {
            die('Error preparing the SQL statement.');
        }
        $conn->close();
    }
}

// Handle Student Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roll_no'])) {
    include 'faculty/db_connect.php';

    // Verify hCaptcha
    $hcaptchaResponse = $_POST['h-captcha-response'];
    $secretKey = $config['HCAPTCHA_SECRET_KEY'];

    $verifyUrl = 'https://hcaptcha.com/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $hcaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ]
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($verifyUrl, false, $context);
    $resultJson = json_decode($result, true);

    if ($resultJson['success'] !== true) {
        $error = 'hCaptcha verification failed. Please try again.';
    } else {
        // Sanitize user inputs
        $input_roll_no = htmlspecialchars($_POST['roll_no']);
        $input_dob = htmlspecialchars($_POST['dob']);

        // Prepare and execute SQL statement securely
        $sql = "SELECT * FROM students WHERE roll_no = ? AND dob = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $input_roll_no, $input_dob);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['student_logged_in'] = true;
                $_SESSION['roll_no'] = $input_roll_no;
                $stmt->close();
                $conn->close();
                header('Location: student/student_login.php');
                exit();
            } else {
                $show_alert = true; // Set flag for invalid credentials
            }
            
            $stmt->close();
        } else {
            die('Error preparing the SQL statement.');
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login Page</title>
    <style>
        /* Modern Professional Theme */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #333;
        }
        .header {
            width: 100%;
            height: auto;
        }
        .header img {
            width: 100%;
            height: auto;
        }
        .banner {
            margin-top: 0;
            padding: 0;
            background-color: #003366;
            color: white;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .banner marquee {
            font-size: 16px;
            font-weight: bold;
        }
        .main-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding: 0 15px;
        }
        .container,
        .notice_board {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            margin: 10px;
            width: 30%;
        }
        h2 {
            font-size: 22px;
            color: #6a11cb;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .input-group {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin: 10px 0;
        }
        input {
            flex: 1;
            max-width: 350px;
            padding: 10px;
            margin: 0 5px;
            border: 2px solid #ddd;
            border-radius: 6px;
            background-color: #f4f4f4;
            font-size: 1em;
            color: #333;
        }
        .eye-icon {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            padding-left: 6.5%;
        }
        .notice_board marquee p {
            color: red;
        }
        .eye-icon input {
            width: 100%;
        }
        .eye-icon i {
            position: absolute;
            right: 10%;
            color: grey;
            cursor: pointer;
        }
        button {
            background-color: #2575fc;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #6a11cb;
        }
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                align-items: center;
            }
            .header {
                width: 100%;
                height: 70px;
            }
            .container,
            .notice_board {
                width: 80%;
            }
        }
        .alert {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
</head>

<body>
    <div class="header">
        <img src="assets/789asdfkl.webp" alt="LMS Portal Image">
    </div>
    <div class="banner">
        <marquee behavior="scroll" direction="left">
            <p>Welcome to the Learning Management System</p>
        </marquee>
    </div>
    <div class="main-container">
        <div class="container">
            <h2>Institution Login</h2>
            <form id="loginForm" action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <div class="eye-icon">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="fas fa-eye-slash icon"></i>
                </div>
                <div class="h-captcha" data-sitekey="<?php echo $config['HCAPTCHA_SITE_KEY']; ?>"></div>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="container">
            <h2>Student Login</h2>
            <form id="studentLoginForm" action="" method="POST">
                <input type="text" name="roll_no" placeholder="Roll Number" required>
                <input type="text" name="dob" placeholder="Date of Birth (DD/MM/YYYY)" required>
                <div class="h-captcha" data-sitekey="<?php echo $config['HCAPTCHA_SITE_KEY']; ?>"></div>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="notice_board">
            <h2>Notice Board</h2>
            <marquee behavior="scroll" direction="left">
                <p>Important: Faculty and Student Login Details are available on the portal.</p>
                <p>Note: The system will be down for maintenance from 2:00 AM to 4:00 AM tomorrow.</p>
                <p>Reminder: Mark your attendance before the deadline to avoid penalties.</p>
            </marquee>
        </div>
    </div>

    <script>
        document.querySelector('.icon').addEventListener('click', function () {
            let passwordInput = document.getElementById('password');
            let icon = document.querySelector('.icon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        });

        // AJAX form submission for Institution Login
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting

                $.ajax({
                    url: '', // The same page
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.includes('Invalid username or password')) {
                            alert('Invalid username or password');
                        } else {
                            window.location.href = 'faculty/home.php';
                        }
                    }
                });
            });

            // AJAX form submission for Student Login
            $('#studentLoginForm').on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting

                $.ajax({
                    url: '', // The same page
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.includes('Invalid roll number or date of birth')) {
                            alert('Invalid roll number or date of birth');
                        } else {
                            window.location.href = 'student/student_login.php';
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
