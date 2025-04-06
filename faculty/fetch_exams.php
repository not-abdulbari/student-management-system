<?php
require_once 'db_connection.php'; // Include database connection

$branch = $_GET['branch'] ?? '';
$year = $_GET['year'] ?? '';
$section = $_GET['section'] ?? '';

$stmt = $pdo->prepare("SELECT DISTINCT exam FROM university_results WHERE reg_no IN (SELECT reg_no FROM students WHERE branch = ? AND year = ? AND section = ?) ORDER BY exam");
$stmt->execute([$branch, $year, $section]);
$exams = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($exams as $exam) {
    echo "<option value=\"$exam\">$exam</option>";
}
?>
