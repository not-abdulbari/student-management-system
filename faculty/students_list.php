<?php
//require_once '../vendor/autoload.php'; // Ensure the path is correct for your project
include 'db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $semester = $_POST['semester'];
    $exam = $_POST['exam'];

    // Fetch students based on criteria
    $sql = "SELECT roll_no, name FROM students WHERE branch = ? AND year = ? AND section = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $branch, $year, $section);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students List</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Students List</h2>
    <table>
        <tr>
            <th>Roll No</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['roll_no']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>
                <form action="generate_pdf.php" method="post" style="margin: 0;">
                    <input type="hidden" name="roll_no" value="<?= htmlspecialchars($row['roll_no']) ?>">
                    <input type="hidden" name="branch" value="<?= htmlspecialchars($branch) ?>">
                    <input type="hidden" name="year" value="<?= htmlspecialchars($year) ?>">
                    <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
                    <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">
                    <input type="hidden" name="exam" value="<?= htmlspecialchars($exam) ?>">
                    <button type="submit" class="btn">Generate PDF</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>