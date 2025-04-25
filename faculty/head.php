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
            font-size: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Header Container */
        .banner {
            background-color: #4caf50;
            color: #ffffff;
            padding: 15px;
            text-align: center;
            width: 100%;
        }

        .banner h1 {
            margin-bottom: 5px;
        }

        .datetime {
            font-size: 14px;
            font-weight: 600;
        }

        /* Navigation Styles */
        nav {
            background-color: #3f51b5;
            width: 100%;
            position: relative;
        }

        nav .menu-toggle {
            display: none;
            background-color: #3f51b5;
            color: white;
            border: none;
            font-size: 24px;
            padding: 10px;
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
        }

        /* Dropdown Menu */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #3f51b5;
            text-align: left;
            padding: 10px;
            border-radius: 5px;
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

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            nav ul {
                display: none;
                flex-direction: column;
                align-items: flex-start;
                background-color: #3f51b5;
                width: 100%;
                position: absolute;
                top: 50px;
                left: 0;
                z-index: 1000;
            }

            nav ul.show {
                display: flex;
            }

            nav .menu-toggle {
                display: block;
            }

            nav ul li {
                margin: 10px 0;
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
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
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
                <a href="javascript:void(0)">STUDENT</a>
                <div class="dropdown-content">
                    <a href="student_login.php">STUDENT LOGIN</a>
                    <a href="student_profile.php">Under Development</a>
                </div>
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

        function toggleMenu() {
            const nav = document.querySelector('nav ul');
            nav.classList.toggle('show');
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>

</body>
</html>
