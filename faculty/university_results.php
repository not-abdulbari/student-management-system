<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db_connect.php'; // Include database connection

try {
    // Fetch distinct branches, years, and sections from students table
    $branches = fetchDistinct('branch');
    $years = fetchDistinct('year');
    $sections = fetchDistinct('section');
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

function fetchDistinct($column) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT DISTINCT $column FROM students ORDER BY $column");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>University Results Filter</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Filter Students</h1>
    <form id="filterForm">
        Branch: 
        <select name="branch" id="branch">
            <option value="">Select Branch</option>
            <?php foreach ($branches as $branch) { echo "<option value=\"$branch\">$branch</option>"; } ?>
        </select>
        Year: 
        <select name="year" id="year">
            <option value="">Select Year</option>
            <?php foreach ($years as $year) { echo "<option value=\"$year\">$year</option>"; } ?>
        </select>
        Section: 
        <select name="section" id="section">
            <option value="">Select Section</option>
            <?php foreach ($sections as $section) { echo "<option value=\"$section\">$section</option>"; } ?>
        </select>
        Exam: 
        <select name="exam" id="exam">
            <option value="">Select Exam</option>
        </select>
        <button type="button" id="fetchStudents">Fetch Students</button>
    </form>
    
    <div id="studentList"></div>
    
    <script>
        $(document).ready(function() {
            // Populate exam dropdown based on selected filters
            $('#branch, #year, #section').change(function() {
                let branch = $('#branch').val();
                let year = $('#year').val();
                let section = $('#section').val();
                if (branch && year && section) {
                    $.ajax({
                        url: 'fetch_exams.php',
                        method: 'GET',
                        data: { branch: branch, year: year, section: section },
                        success: function(data) {
                            $('#exam').html(data);
                        }
                    });
                }
            });

            // Fetch students using AJAX
            $('#fetchStudents').click(function() {
                $.ajax({
                    url: 'university_studentlist.php',
                    method: 'GET',
                    data: $('#filterForm').serialize(),
                    success: function(data) {
                        $('#studentList').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
