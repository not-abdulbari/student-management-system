<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
            margin: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Content Container */
        .content-container {
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Module Tutorial */
        .module-tutorial {
            margin-bottom: 20px;
        }

        .module-tutorial h2 {
            color: #3498db;
            margin-bottom: 10px;
        }

        .module-tutorial p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        /* Responsive Layout */
        @media screen and (max-width: 768px) {
            .module-tutorial p {
                font-size: 13px;
            }
        }

    </style>
</head>
<body>

    <?php
        session_start();

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ../index.php');
            exit;
        }
        include 'head.php'; // Including the header with the navigation and banner
    ?>

    <div class="content-container">
        <div class="module-tutorial">
            <h2>Module Tutorial</h2>
            <p>Welcome to the tutorial section. Here you will find instructions on how to use each module within the system.</p>

            <h3>Marks Module</h3>
            <p>This module allows you to manage and enter marks for students. To get started, navigate to the 'Marks' section from the main menu.</p>

            <h3>Attendance Module</h3>
            <p>This module helps you track and manage student attendance. You can mark attendance, view attendance reports, and generate attendance summaries. Find this module under the 'Attendance' section.</p>

            <h3>Grade Module</h3>
            <p>In this module, you can add or update student grades. Access this module through the 'Grade' section in the main menu.</p>

            <h3>Subject Module</h3>
            <p>The subject management module lets you create and manage subjects offered by the college. Go to the 'Subject' section to begin.</p>

            <h3>Students Report Module</h3>
            <p>This module allows you to view and generate reports for students. Navigate to the 'Students Report' section to use this feature.</p>

            <h3>Reports Module</h3>
            <p>Manage and generate different types of reports using this module. You can find it under the 'Reports' section in the main menu.</p>

            <h3>Student Login Module</h3>
            <p>Parents and students can access their profiles and view relevant information through this module. Visit the 'Student' section to use this feature.</p>
        </div>
    </div>

</body>
</html>
