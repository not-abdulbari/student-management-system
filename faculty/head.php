<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../index.php');
    exit;
}
if (!function_exists('getCurrentDateTime')) {
    function getCurrentDateTime() {
        return date('Y-m-d H:i:s');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to C. Abdul Hakeem College of Engineering & Technology</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMt23cez/3paNdF+GZPl+4tJH9Aa71wQK5R5h7g" crossorigin="anonymous">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            font-size: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Header Banner */
        .banner {
            background-color: #004085;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            width: 100%;
        }

        .banner h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .datetime {
            font-size: 14px;
            font-weight: 600;
            margin-top: 5px;
        }

        /* Navigation Styles */
        nav {
            background-color: #343a40;
            width: 100%;
        }

        nav .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            color: #ffffff;
        }

        nav .menu .hamburger {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        nav ul li a:hover {
            color: #f8f9fa;
        }

        /* Dropdown Menu */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #343a40;
            text-align: left;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
        }

        .dropdown-content a:hover {
            background-color: #495057;
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            nav .menu .hamburger {
                display: block;
            }

            nav ul {
                display: none;
                flex-direction: column;
                align-items: flex-start;
                background-color: #343a40;
                width: 100%;
                position: absolute;
                top: 60px;
                left: 0;
                z-index: 1000;
            }

            nav ul.show {
                display: flex;
            }

            nav ul li {
                margin: 10px 0;
                padding-left: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="banner">
        <h1>Welcome - C. Abdul Hakeem College of Engineering & Technology</h1>
        <div class="datetime" id="datetime"><?php echo getCurrentDateTime(); ?></div>
    </div>

    <nav>
        <div class="menu">
            <span class="hamburger" onclick="toggleMenu()">☰</span>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="faculty_dashboard.php">Marks</a></li>
                <li><a href="attendance_dashboard.php">Attendance</a></li>
                <li><a href="add_grades.php">Grade</a></li>
                <li><a href="add_subject.php">Subject</a></li>
                <li><a href="student_report.php">Students Report</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)">Reports</a>
                    <div class="dropdown-content">
                        <a href="report_selection.php">Result Analysis</a>
                        <a href="generate_marksheet.php">Mark List</a>
                        <a href="progress_prelims.php">Progress Report</a>
                        <a href="class_performance.php">Consolidated Result Analysis</a>
                        <a href="capa_select.php">CAPA Form</a>
                        <a href="generate_namelist.php">NAMELIST</a>
                        <a href="consolidated_marklist.php">Consolidated Marklist</a>
                        <a href="university_results.php">University Progress Report</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">Student</a>
                    <div class="dropdown-content">
                        <a href="student_login.php">Student Login</a>
                        <a href="student_profile.php">Under Development</a>
                    </div>
                </li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <script>
        function updateDateTime() {
            const datetimeElement = document.getElementById("datetime");
            const now = new Date();
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            datetimeElement.innerText = now.toLocaleString('en-US', options);
        }

        function toggleMenu() {
            const nav = document.querySelector('nav ul');
            nav.classList.toggle('show');
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>

</body>
</html>
