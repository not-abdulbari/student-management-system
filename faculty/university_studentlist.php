<?php
require_once 'db_connection.php'; // Include database connection

$branch = $_GET['branch'] ?? '';
$year = $_GET['year'] ?? '';
$section = $_GET['section'] ?? '';
$exam = $_GET['exam'] ?? '';

$stmt = $pdo->prepare("SELECT roll_no, reg_no, name, branch, year, section FROM students WHERE branch = ? AND year = ? AND section = ? AND reg_no IN (SELECT reg_no FROM university_results WHERE exam = ?)");
$stmt->execute([$branch, $year, $section, $exam]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
</head>
<body>
    <h1>Student List</h1>
    <form id="studentListForm">
        <table border="1">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Roll No</th>
                    <th>Reg No</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Year</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) { ?>
                <tr>
                    <td><input type="checkbox" name="students[]" value="<?= $student['roll_no'] ?>"></td>
                    <td><?= $student['roll_no'] ?></td>
                    <td><?= $student['reg_no'] ?></td>
                    <td><?= $student['name'] ?></td>
                    <td><?= $student['branch'] ?></td>
                    <td><?= $student['year'] ?></td>
                    <td><?= $student['section'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="button" id="generateReport">Generate Report</button>
    </form>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('input[name="students[]"]');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        document.getElementById('generateReport').addEventListener('click', function() {
            let form = document.getElementById('studentListForm');
            let formData = new FormData(form);
            fetch('generate_univres.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.blob())
            .then(blob => {
                let url = window.URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'University_Report.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
            });
        });
    </script>
</body>
</html>
