<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../index.php');
    exit;
}
include 'head.php';
include 'db_connect.php'; // Include your database connection file

// Fetch branches
$branches_sql = "SELECT DISTINCT branch FROM students";
$branches_result = $conn->query($branches_sql);

// Fetch years
$years_sql = "SELECT DISTINCT year FROM students";
$years_result = $conn->query($years_sql);

// Fetch sections
$sections_sql = "SELECT DISTINCT section FROM students";
$sections_result = $conn->query($sections_sql);

// Fetch exam types from marks table
$exam_types_sql = "SELECT DISTINCT exam FROM marks";
$exam_types_result = $conn->query($exam_types_sql);

// Fetch semesters (sorted order)
$semesters_sql = "SELECT DISTINCT semester FROM marks ORDER BY semester ASC";
$semesters_result = $conn->query($semesters_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marks Criteria</title>
    <style>
        /* Base styling for body */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f7f9fc, #e4f1fe);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            margin: 40px 0;
            font-weight: bold;
        }

        form {
            max-width: 800px;
            margin: 30px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
            border-left: 5px solid #3498db;
        }

        form:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        label {
            margin-top: 15px;
            display: block;
            font-weight: bold;
            color: #2c3e50;
            font-size: 16px;
        }

        select, button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
            transition: background-color 0.3s, border-color 0.3s;
        }

        select:focus, button:focus {
            outline: none;
            border-color: #3498db;
        }

        select:hover, button:hover {
            background-color: #eaf2f8;
        }

        button {
            background-color: #3498db;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        button:active {
            transform: scale(1);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 250px;
        }

        .form-row select {
            width: 100%;
        }

        .form-row .form-group label {
            font-weight: normal;
            font-size: 16px;
        }

        select option:hover {
            background-color: #f1f1f1;
        }

        button:focus {
            outline: none;
            border-color: #3498db;
        }

        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-row .form-group {
                width: 100%;
            }
        }

        form {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        input::placeholder,
        select::placeholder {
            color: #bbb;
        }

        body {
            background: linear-gradient(135deg, #f7f9fc, #e4f1fe);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Marks Criteria</h2>
        <form action="generate_counselling_list.php" method="post">
            <div class="form-group">
                <label for="branch">Branch:</label>
                <select id="branch" name="branch" required>
                    <option value="">Select Branch</option>
                    <?php while ($row = $branches_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['branch']) ?>"><?= htmlspecialchars($row['branch']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year of Passing:</label>
                <select id="year" name="year" required>
                    <option value="">Select Year of Passing</option>
                    <?php while ($row = $years_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['year']) ?>"><?= htmlspecialchars($row['year']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="dropdown-group">
                <label>Year (Roman):</label>
                <select name="year_roman" required>
                    <option value="">Select Year</option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                </select>
            </div>
            <div class="form-group">
                <label for="section">Section:</label>
                <select id="section" name="section" required>
                    <option value="">Select Section</option>
                    <?php while ($row = $sections_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['section']) ?>"><?= htmlspecialchars($row['section']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="dropdown-group">
                <label>Batch:</label>
                <select name="batch" id="batch" required>
                    <option value="">Select Batch</option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                </select>
            </div>
            <div class="dropdown-group">
                <label>Semester:</label>
                <select name="semester" id="semester" required>
                    <option value="">Select Semester</option>
                    <?php while ($row = $semesters_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['semester']) ?>"><?= htmlspecialchars($row['semester']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="exam">Exam:</label>
                <select id="exam" name="exam" required>
                    <option value="">Select Exam</option>
                    <?php while ($row = $exam_types_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['exam']) ?>"><?= htmlspecialchars($row['exam']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="faculty_code">Faculty Code:</label>
                <input type="text" id="faculty_code" name="faculty_code" placeholder="Enter Faculty Code" required>
            </div>
            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>