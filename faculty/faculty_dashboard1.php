<?php
session_start();

include 'head.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: faculty_login.php');
    exit;
}

// Database connection
include 'db_connect.php';

// Reusable function to fetch distinct values for dropdowns
function fetchDistinctValues($conn, $column, $table) {
    $stmt = $conn->prepare("SELECT DISTINCT $column FROM $table ORDER BY $column ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $values = [];
    while ($row = $result->fetch_assoc()) {
        $values[] = htmlspecialchars($row[$column]);
    }
    $stmt->close();
    return $values;
}

// Fetch dropdown values
$branches = fetchDistinctValues($conn, 'branch', 'students');
$years = fetchDistinctValues($conn, 'year', 'students');
$sections = fetchDistinctValues($conn, 'section', 'students');
$semesters = fetchDistinctValues($conn, 'semester', 'subjects');

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <style>
        /* Your CSS styles here */
    </style>
    <script>
        function fetchSubjects() {
            const branch = document.getElementById("branch").value;
            const semester = document.getElementById("semester").value;
            const subjectSelect = document.getElementById("subject");

            // Reset subject dropdown
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';

            if (branch && semester) {
                fetch(`get_subjects.php?branch=${encodeURIComponent(branch)}&semester=${encodeURIComponent(semester)}`)
                    .then(response => response.json())
                    .then(subjects => {
                        if (subjects.error) {
                            console.error(subjects.error);
                            alert("Error fetching subjects: " + subjects.error);
                        } else {
                            subjects.forEach(subject => {
                                const option = document.createElement("option");
                                option.value = subject.subject_code;
                                option.textContent = subject.subject_name;
                                subjectSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => console.error("Error fetching subjects:", error));
            }
        }
    </script>
</head>
<body>
    <h2>Marks Entry</h2>
    <form action="marks_entry.php" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="branch">Select Branch:</label>
                <select name="branch" id="branch" onchange="fetchSubjects()" required>
                    <option value="">Select Branch</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch ?>"><?= $branch ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="year">Select Year of Passing:</label>
                <select name="year" id="year" required>
                    <option value="">Select Year of Passing</option>
                    <?php foreach ($years as $year): ?>
                        <option value="<?= $year ?>"><?= $year ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="section">Select Section:</label>
                <select name="section" id="section" required>
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= $section ?>"><?= $section ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="semester">Select Semester:</label>
                <select name="semester" id="semester" onchange="fetchSubjects()" required>
                    <option value="">Select Semester</option>
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?= $semester ?>"><?= $semester ?> Semester</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="subject">Select Subject:</label>
                <select name="subject" id="subject" required>
                    <option value="">Select Subject</option>
                </select>
            </div>

            <div class="form-group">
                <label for="exam">Select Exam Type:</label>
                <select name="exam" id="exam" required>
                    <option value="">Select Exam</option>
                    <option value="CAT1">CAT1</option>
                    <option value="CAT2">CAT2</option>
                    <option value="Model">Model</option>
                </select>
            </div>
        </div>

        <button type="submit">Go to Mark Entry</button>
    </form>
</body>
</html>
