<?php
require_once 'db_connect.php'; // Include database connection
require_once '../fpdf186/fpdf.php'; // Include FPDF library

$students = $_POST['students'] ?? [];

foreach ($students as $roll_no) {
    $stmt = $pdo->prepare("SELECT s.roll_no, s.reg_no, s.name, s.branch, s.year, s.section, r.exam, r.semester, r.subject_code, r.grade, sub.subject_name
                           FROM students s
                           JOIN university_results r ON s.reg_no = r.reg_no
                           JOIN subjects sub ON r.subject_code = sub.subject_code
                           WHERE s.roll_no = ?");
    $stmt->execute([$roll_no]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        $student = $results[0]; // Get student details
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // University header
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'University Exam Results', 0, 1, 'C');

        // Student details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Name: ' . $student['name'], 0, 1);
        $pdf->Cell(0, 10, 'Roll No: ' . $student['roll_no'], 0, 1);
        $pdf->Cell(0, 10, 'Reg No: ' . $student['reg_no'], 0, 1);
        $pdf->Cell(0, 10, 'Branch: ' . $student['branch'], 0, 1);
        $pdf->Cell(0, 10, 'Year: ' . $student['year'], 0, 1);
        $pdf->Cell(0, 10, 'Section: ' . $student['section'], 0, 1);
        $pdf->Cell(0, 10, 'Exam: ' . $student['exam'], 0, 1);

        // Results table
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Subject Code', 1);
        $pdf->Cell(100, 10, 'Subject Name', 1);
        $pdf->Cell(50, 10, 'Grade', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($results as $result) {
            $pdf->Cell(40, 10, $result['subject_code'], 1);
            $pdf->Cell(100, 10, $result['subject_name'], 1);
            $pdf->Cell(50, 10, $result['grade'], 1);
            $pdf->Ln();
        }

        // Signature section
        $pdf->Ln(20);
        $pdf->Cell(0, 10, '__________________________', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Signature', 0, 1, 'C');

        // Footer with page number
        $pdf->AliasNbPages();
        $pdf->Cell(0, 10, 'Page ' . $pdf->PageNo() . ' of {nb}', 0, 0, 'C');

        // Output PDF
        $filename = $student['roll_no'] . '-University-' . str_replace(' ', '-', $student['exam']) . '.pdf';
        $pdf->Output('D', $filename);
    }
}
