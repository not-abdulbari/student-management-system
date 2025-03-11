<?php
include 'db_connect.php';

// Fetch distinct values from marks table
$branches = $conn->query("SELECT DISTINCT branch FROM marks WHERE branch IS NOT NULL");
$years = $conn->query("SELECT DISTINCT year FROM marks WHERE year IS NOT NULL");
$sections = $conn->query("SELECT DISTINCT section FROM marks WHERE section IS NOT NULL");
$semesters = $conn->query("SELECT DISTINCT semester FROM marks WHERE semester IS NOT NULL");
$subjects = $conn->query("SELECT DISTINCT subject FROM marks WHERE subject IS NOT NULL");
$exams = $conn->query("SELECT DISTINCT exam FROM marks WHERE exam IS NOT NULL");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Report Parameters</title>
    <style>
        .form-container { max-width: 600px; margin: 20px auto; padding: 20px; }
        .dropdown-group { margin: 10px 0; }
        select { width: 100%; padding: 8px; margin: 5px 0; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Select Report Parameters</h2>
        <form method="post" action="generate_report.php">
            <div class="dropdown-group">
                <label>Branch:</label>
                <select name="branch" required>
                    <?php while($row = $branches->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($row['branch']) ?>"><?= htmlspecialchars($row['branch']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Repeat similar structure for other dropdowns -->
            <div class="dropdown-group">
                <label>Year:</label>
                <select name="year" required>
                    <?php while($row = $years->fetch_assoc()) { ?>
                        <option><?= htmlspecialchars($row['year']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Section:</label>
                <select name="section" required>
                    <?php while($row = $sections->fetch_assoc()) { ?>
                        <option><?= htmlspecialchars($row['section']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Semester:</label>
                <select name="semester" required>
                    <?php while($row = $semesters->fetch_assoc()) { ?>
                        <option><?= htmlspecialchars($row['semester']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Subject:</label>
                <select name="subject" required>
                    <?php while($row = $subjects->fetch_assoc()) { ?>
                        <option><?= htmlspecialchars($row['subject']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="dropdown-group">
                <label>Exam Type:</label>
                <select name="exam" required>
                    <?php while($row = $exams->fetch_assoc()) { ?>
                        <option><?= htmlspecialchars($row['exam']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit">Generate Report</button>
        </form>
    </div>
</body>
</html>