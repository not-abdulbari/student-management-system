<?php
require('../fpdf186/fpdf.php');
include 'db_connection.php';

$branch = $_GET['branch'];
$year = $_GET['year'];
$section = $_GET['section'];
$exam = $_GET['exam'];

$query = "SELECT s.roll_no, s.reg_no, s.name, ur.exam, ur.subject_code, ur.grade 
          FROM students s 
          JOIN university_results ur ON s.reg_no = ur.reg_no 
          WHERE s.branch = '$branch' AND s.year = '$year' AND s.section = '$section' AND ur.exam = '$exam'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'University Exam Results', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Roll No: ' . $row['roll_no'], 0, 1);
    $pdf->Cell(0, 10, 'Reg No: ' . $row['reg_no'], 0, 1);
    $pdf->Cell(0, 10, 'Name: ' . $row['name'], 0, 1);
    $pdf->Cell(0, 10, 'Exam: ' . $row['exam'], 0, 1);

    $subjectQuery = "SELECT subject_code, subject_name FROM subjects WHERE subject_code = '" . $row['subject_code'] . "'";
    $subjectResult = mysqli_query($conn, $subjectQuery);
    $subjectRow = mysqli_fetch_assoc($subjectResult);

    $pdf->Cell(0, 10, 'Subject: ' . $subjectRow['subject_name'] . ' (' . $subjectRow['subject_code'] . ')', 0, 1);
    $pdf->Cell(0, 10, 'Grade: ' . $row['grade'], 0, 1);

    $filename = $row['roll_no'] . '-University-' . $exam . '.pdf';
    $pdf->Output('D', $filename);
}
?>
