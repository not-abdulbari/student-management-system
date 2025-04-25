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
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Banner */
        .banner {
            background-color: #4a148c; /* Royal purple */
            color: #ffffff; /* Permanent white */
            padding: 15px;
            text-align: center;
            border-bottom: 3px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .datetime {
            font-size: 14px;
            margin-top: 5px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Navigation */
        nav {
            background-color: #512da8; /* Royal purple navigation bar */
            color: #ffffff;
            width: 100%;
            position: relative;
        }

        nav .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        nav .menu .logo {
            font-size: 18px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: none; /* Hidden for mobile view */
            flex-direction: column;
            background-color: #673ab7; /* Slightly lighter royal purple for dropdown */
            position: absolute;
            top: 50px;
            left: 0;
            right: 0;
            padding: 10px 0;
        }

        nav ul.show {
            display: flex;
        }

        nav ul li {
            text-align: center;
            padding: 10px 0;
        }

        nav ul li a {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
        }

        nav ul li a:hover {
            background-color: #9575cd; /* Hover lighter royal purple */
            border-radius: 5px;
        }

        /* Hamburger Button */
        .hamburger {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 20px;
        }

        .hamburger div {
            width: 25px;
            height: 3px;
            background-color: #ffffff;
        }

        /* Responsive Design */
        @media screen and (min-width: 768px) {
            nav ul {
                display: flex;
                flex-direction: row;
                position: static;
                background-color: transparent;
                padding: 0;
            }

            nav ul li {
                padding: 0;
            }

            nav ul li a {
                padding: 10px 15px;
            }

            .hamburger {
                display: none;
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
            <div class="logo">CAHCET</div>
            <div class="hamburger" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <ul id="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="faculty_dashboard.php">Marks</a></li>
            <li><a href="attendance_dashboard.php">Attendance</a></li>
            <li><a href="add_grades.php">Grade</a></li>
            <li><a href="add_subject.php">Subject</a></li>
            <li><a href="student_report.php">Students Report</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)">Reports</a>
                <ul class="dropdown-content">
                    <li><a href="report_selection.php">Result Analysis</a></li>
                    <li><a href="generate_marksheet.php">Mark List</a></li>
                    <li><a href="progress_prelims.php">Progress Report</a></li>
                    <li><a href="class_performance.php">Consolidated Result Analysis</a></li>
                    <li><a href="capa_select.php">CAPA Form</a></li>
                    <li><a href="generate_namelist.php">NAMELIST</a></li>
                    <li><a href="consolidated_marklist.php">Consolidated Marklist</a></li>
                    <li><a href="university_results.php">University Progress Report</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">STUDENT</a>
                <ul class="dropdown-content">
                    <li><a href="student_login.php">STUDENT LOGIN</a></li>
                    <li><a href="student_profile.php">Under Development</a></li>
                </ul>
            </li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
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

        setInterval(updateDateTime, 1000);
        updateDateTime();

        function toggleMenu() {
            const navLinks = document.getElementById("nav-links");
            navLinks.classList.toggle("show");
        }
    </script>
</body>
</html>
