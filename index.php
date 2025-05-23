<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Turnstile configuration
$config = include 'config.php';

// Handle Institution Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $turnstile_response = $_POST['cf-turnstile-response'] ?? '';
    if (empty($turnstile_response)) {
        echo json_encode(['status' => 'error', 'message' => 'Please complete the Turnstile verification.']);
        exit();
    }

    // Verify Turnstile response
    $data = [
        'secret' => $config['TURNSTILE_SECRET_KEY'],
        'response' => $turnstile_response,
    ];
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
    $result = json_decode($response, true);

    if (!$result['success']) {
        echo json_encode(['status' => 'error', 'message' => 'Turnstile verification failed.']);
        exit();
    }

    // Proceed with login logic
    include 'faculty/db_connect.php';
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    $input_hashed_password = hash('sha256', $input_password);
    $sql = "SELECT hashed_password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $input_username);
    $stmt->execute();
    $stmt->bind_result($stored_hashed_password);
    $stmt->fetch();
    if ($input_hashed_password === $stored_hashed_password) {
        $_SESSION['logged_in'] = true;
        $stmt->close();
        $conn->close();
        echo json_encode(['status' => 'success', 'redirect' => 'faculty/home.php']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        exit();
    }
}

// Handle Student Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['roll_no'])) {
    $turnstile_response = $_POST['cf-turnstile-response'] ?? '';
    if (empty($turnstile_response)) {
        echo json_encode(['status' => 'error', 'message' => 'Please complete the Turnstile verification.']);
        exit();
    }

    // Verify Turnstile response
    $data = [
        'secret' => $config['TURNSTILE_SECRET_KEY'],
        'response' => $turnstile_response,
    ];
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
    $result = json_decode($response, true);

    if (!$result['success']) {
        echo json_encode(['status' => 'error', 'message' => 'Turnstile verification failed.']);
        exit();
    }

    // Proceed with login logic
    include 'faculty/db_connect.php';
    $roll_no = $_POST['roll_no'];
    $dob_input = $_POST['dob'];
    // Convert dd-mm-yyyy to yyyy-mm-dd for database comparison
    $dob_parts = explode('-', $dob_input);
    if (count($dob_parts) === 3) {
        $dob_db_format = $dob_parts[2] . '-' . $dob_parts[1] . '-' . $dob_parts[0];
    } else {
        $dob_db_format = $dob_input; // fallback if format is unexpected
    }
    // Query to check if the roll number and DOB match
    $sql = "SELECT si.dob FROM students s JOIN student_information si ON s.roll_no = si.roll_no WHERE s.roll_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $roll_no);
    $stmt->execute();
    $stmt->bind_result($stored_dob);
    $stmt->fetch();
    $stmt->close();
    if ($stored_dob && $dob_db_format === $stored_dob) {
        // Credentials match, set session and redirect
        $_SESSION['student_roll_no'] = $roll_no;
        $conn->close();
        echo json_encode(['status' => 'success', 'redirect' => 'student/student_login.php']);
        exit();
    } else {
        // Invalid credentials
        echo json_encode(['status' => 'error', 'message' => 'Invalid roll number or date of birth']);
        exit();
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
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <title>CAHCET LMS - Login</title>
    <style>
               /* Modern Professional Theme */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #00838F 0%, #005B63 100%);
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
            background-color: #00838F;
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
            width: 100%;
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
            background-color: #00838F;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #005B63;
        }
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 6px;
            background-color: #f4f4f4;
            font-size: 1em;
            color: #333;
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
</head>
<body>
    <div class="header">
        <img src="assets/789asdfkl.webp" alt="Sunridge University LMS Portal">
    </div>
    <div class="banner">
        <marquee behavior="scroll" direction="left">
            <p>Welcome to the CAHCET - Learning Management System</p>
        </marquee>
    </div>
    <div class="main-container">
        <div class="container">
            <h2>Institution Login</h2>
            <form id="loginForm" action="" method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input style="width: 100%;" type="password" name="password" placeholder="Password" required>
                </div>
                <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars($config['TURNSTILE_SITE_KEY']); ?>" data-theme="light"></div>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="container">
            <h2>Student Login</h2>
            <form id="studentLoginForm" action="" method="POST">
                <div class="input-group">
                    <input type="text" name="roll_no" placeholder="Roll Number" required>
                </div>
                <div class="input-group">
                    <input type="text" name="dob" placeholder="Date of Birth (DD-MM-YYYY)" required>
                </div>
                <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars($config['TURNSTILE_SITE_KEY']); ?>" data-theme="light"></div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

