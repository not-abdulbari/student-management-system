<?php
include 'db_connect.php';
include 'head.php';

// Fetch distinct values for dropdowns
$branches = $conn->query("SELECT DISTINCT branch FROM marks WHERE branch IS NOT NULL ORDER BY branch ASC");
$years = $conn->query("SELECT DISTINCT year FROM marks WHERE year IS NOT NULL ORDER BY year ASC");
$sections = $conn->query("SELECT DISTINCT section FROM marks WHERE section IS NOT NULL ORDER BY section ASC");
$semesters = $conn->query("SELECT DISTINCT semester FROM marks WHERE semester IS NOT NULL ORDER BY CAST(semester AS UNSIGNED) ASC");
$subjects = $conn->query("SELECT DISTINCT subject FROM marks WHERE subject IS NOT NULL ORDER BY subject ASC");
$exams = $conn->query("SELECT DISTINCT exam FROM marks WHERE exam IS NOT NULL ORDER BY exam ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Report Parameters</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .dropdown-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        select, input[type="text"], input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #555;
            color: white;
            text-decoration: none;
            text-align: center;
            padding: 10px 15px;
            display: inline-block;
        }

        .btn-back:hover {
            background-color: #333;
        }
    </style>

    <script>
        $(document).ready(function () {
            // Fetch subjects dynamically when branch & semester are selected
            $("#branch, #semester").change(function () {
                var branch = $("#branch").val();
                var semester = $("#semester").val();

                if (branch !== "" && semester !== "") {
                    $.ajax({
                        type: "POST",
                        url: "get_subject.php",
                        data: { branch: branch, semester: semester },
                        success: function (response) {
                            $("#subject").html(response);
                        }
                    });
                }
            });
        });
    </script>

</head>
<body>

    <div class="container">
        <h2>Select Report Parameters</h2>
        <form method="post" action="generate_report.php">
            <div class="dropdown-group">
                <label>Branch:</label>
                <select name="branch" id="branch" required>
                    <option value="">Select Branch</option>
                    <?php while ($row = $branches->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($row['branch']) ?>"><?= htmlspecialchars($row['branch']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Year:</label>
                <select name="year" required>
                    <option value="">Select Year</option>
                    <?php while ($row = $years->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($row['year']) ?>"><?= htmlspecialchars($row['year']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Section:</label>
                <select name="section" required>
                    <option value="">Select Section</option>
                    <?php while ($row = $sections->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($row['section']) ?>"><?= htmlspecialchars($row['section']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Semester:</label>
                <select name="semester" id="semester" required>
                    <option value="">Select Semester</option>
                    <?php while ($row = $semesters->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($row['semester']) ?>"><?= htmlspecialchars($row['semester']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Subject:</label>
                <select name="subject" id="subject" required>
                    <option value="">Select Semester & Branch First</option>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Exam Type:</label>
                <select name="exam" required>
                    <option value="">Select Exam Type</option>
                    <?php while ($row = $exams->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($row['exam']) ?>"><?= htmlspecialchars($row['exam']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Faculty Code:</label>
                <input type="text" name="faculty_code" required>
            </div>

            <div class="dropdown-group">
                <label>Date of Exam:</label>
                <input type="date" name="exam_date" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="btn-container">
                <a href="faculty_dashboard.php" class="btn btn-back">← Back</a>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>

</body>
</html>
