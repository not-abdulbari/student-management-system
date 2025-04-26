<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../index.php');
    exit;
}

include 'db_connect.php'; // Include your database connection file

// Retrieve form data
$roll_no = $_POST['roll_no'] ?? null;
$branch = $_POST['branch'] ?? null;
$year = $_POST['year'] ?? null;
$section = $_POST['section'] ?? null;
$semester = $_POST['semester'] ?? null;

// Validate required fields
if (!$roll_no || !$branch || !$year || !$section || !$semester) {
    die("All fields are required. Please go back and fill out the form completely.");
}

// Fetch student details
$sql = "SELECT name, reg_no FROM students WHERE roll_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $roll_no);
$stmt->execute();
$student_result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student_result) {
    die("Student details not found. Please check the provided Roll No.");
}

// Retrieve faculty code from session
$faculty_code = $_SESSION['faculty_code'] ?? null;
$facultyName = ""; // Default value

if ($faculty_code) {
    // Fetch faculty name from faculty table
    $facultyNameQuery = $conn->query("SELECT faculty_name FROM faculty WHERE faculty_code='$faculty_code'");
    if ($facultyNameQuery) {
        $facultyNameRow = $facultyNameQuery->fetch_assoc();
        $facultyName = $facultyNameRow['faculty_name'] ?? "Unknown Faculty";
    }
}

// Determine batch based on year
$batch = ($year == "1" || $year == "I") ? "I" : "II";

// Calculate CAY (Current Academic Year)
$cay = date('Y') . '-' . (date('Y') + 1);

// Fetch attendance
$attendance_query = "SELECT percentage FROM semester_attendance WHERE roll_no = ? AND semester = ?";
$stmt = $conn->prepare($attendance_query);
$stmt->bind_param("si", $roll_no, $semester);
$stmt->execute();
$attendance_result = $stmt->get_result()->fetch_assoc();
$attendance_percentage = $attendance_result['percentage'] ?? "";
$stmt->close();

// Fetch marks along with subject name
$marks_query = "SELECT m.subject AS subject_code, m.exam, m.marks, s.subject_name
                FROM marks m
                INNER JOIN subjects s ON m.subject = s.subject_code
                WHERE m.roll_no = ? AND m.branch = ? AND m.year = ? AND m.semester = ? AND m.section = ?";
$stmt = $conn->prepare($marks_query);
$stmt->bind_param("sssis", $roll_no, $branch, $year, $semester, $section);
$stmt->execute();
$marks_result = $stmt->get_result();
$marks_data = [];
while ($row = $marks_result->fetch_assoc()) {
    $marks_data[] = $row;
}
$stmt->close();

// Group marks by subject code and exam type
$grouped_marks = [];
foreach ($marks_data as $mark) {
    $subject_code = $mark['subject_code'];
    $subject_name = $mark['subject_name'];
    $exam = strtoupper($mark['exam']); // Ensure case sensitivity matches the database values
    $grouped_marks[$subject_code]['NAME'] = $subject_name; // Store subject name
    $grouped_marks[$subject_code][$exam] = $mark['marks'];
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselling Form</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.2;
            width: 210mm;
            min-height: 297mm; /* Ensure body takes at least the height of A4 */
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
            display: flex;
            flex-direction: column;
        }
        .container {
            padding: 10mm;
            box-sizing: border-box;
            flex-grow: 1; /* Allow container to take up available vertical space */
        }
        .header-top {
            display: flex;
            align-items: center;
            justify-content: center; /* Added this line to center horizontally */
            margin-bottom: 5px;
        }
        .logo {
            width: 60px;
            height: auto;
            margin-right: 10px;
        }
        .college-info {
            text-align: center;
            font-size: 18px;
        }
        .dept-info {
            text-align: center;
            font-size: 16px;
            margin-top: -5px;
            margin-bottom: 10px;
        }
        .form-title-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .form-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
        }
        .student-info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .student-info-table td {
            padding: 3px;
        }
        .reason-section {
            margin-bottom: 12px;
            font-size: 14px;
        }
        .reason-checkboxes {
            display: flex;
            flex-direction: column;
            margin-left: 15px;
        }
        .reason-checkboxes label {
            margin-bottom: 2px;
            display: block;
        }
        .academic-performance {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .attendance-table {
            width: 40%;
            border-collapse: collapse;
            font-size: 14px;
            margin-left: 15px;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid black;
            padding: 3px;
            text-align: center;
        }
        .marks-section {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .marks-table th, .marks-table td {
            border: 1px solid black;
            padding: 3px;
            text-align: left;
        }
        .arrears-section {
            margin-bottom: 15px;
            font-size: 14px;
        }
        .specified-reason-section {
            margin-bottom: 15px;
            font-size: 14px;
        }
        .specified-reason-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 5px;
        }
        .specified-reason-label {
            width: 160px;
            display: inline-block;
            vertical-align: top;
        }
        .specified-reason-input {
            flex-grow: 1;
            border: none; /* Remove the border */
            outline: none; /* Remove the outline on focus if you want */
            padding: 0; /* Remove padding to eliminate extra space */
            font-size: inherit; /* Inherit font size from parent */
            font-family: inherit; /* Inherit font family from parent */
        }
        .specified-reason-textarea {
            width: calc(100% - 165px);
            padding: 3px;
            font-size: 14px;
            box-sizing: border-box;
            height: 40px;
        }
        .counselor-comment-section,
        .student-declaration-section,
        .class-advisor-comment-section,
        .contact-numbers-section {
            margin-bottom: 15px;
            font-size: 14px;
        }
        .comment-textarea {
            width: 100%;
            padding: 3px;
            font-size: 14px;
            box-sizing: border-box;
            height: 60px;
        }
        .contact-numbers-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 5px;
        }
        .contact-label {
            width: 160px;
            display: inline-block;
        }
        .contact-input {
            width: 200px;
            padding: 3px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .signature-label {
            margin-left: 50px;
            display: inline-block;
            width: 80px;
        }
        .signature-input {
            width: 150px;
            padding: 3px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .signature-row-bottom {
            display: flex;
            justify-content: space-around;
            font-size: 14px;
            margin-top: 20px; /* Add some space above the signature row */
            padding-bottom: 10mm; /* Add padding at the bottom for print margin */
        }
        .signature-col {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid black;
            margin-top: 10px;
            padding-top: 5px;
        }
        @media print {
            .specified-reason-input {
                border: none !important; /* Ensure no border when printing */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-top" style = "text-align: center;">
            <?php
            $logo_path = '../assets/24349bb44aaa1a8c.jpg'; // Replace with the actual path to your logo
            if (file_exists($logo_path)): ?>
                <img src="<?= $logo_path ?>" alt="College Logo" class="logo">
            <?php endif; ?>
            <div class="college-info">
                C. ABDUL HAKEEM COLLEGE OF ENGINEERING & TECHNOLOGY<br>
                MELVISHARAM - 632509
            </div>
        </div>
        <div class="dept-info">
            DEPARTMENT OF <?= htmlspecialchars(strtoupper($branch)) ?>
        </div>
        <div class="form-title-row">
            <div>CAHCET / AD / SSC / <?= htmlspecialchars(strtoupper(substr($branch, 0, 4))) ?> / COUN - 01</div>
            <div class="form-title">COUNSELLING FORM</div>
            <div>Date : <?= htmlspecialchars(date('d-m-Y')) ?></div>
        </div>

        <table class="student-info-table">
            <tr>
                <td>Name of the Counsellor</td>
                <td>:     <?= htmlspecialchars($facultyName) ?></td>
                <td>CAY</td>
                <td>:     <?= htmlspecialchars($cay) ?></td>
            </tr>
            <tr>
                <td>Name of the Student</td>
                <td>:     <?= htmlspecialchars($student_result['name']) ?></td>
                <td>Batch</td>
                <td>:     <?= htmlspecialchars($batch) ?></td>
            </tr>
            <tr>
                <td>Register Number</td>
                <td>:     <?= htmlspecialchars($student_result['reg_no']) ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Year / Sem / Sec</td>
                <td colspan="3">:     <?= htmlspecialchars($year . ' / ' . $semester . ' / ' . $section) ?></td>
            </tr>
        </table>

        <div class="reason-section">
            <b>1. Reason for Counseling:</b>
            <div class="reason-checkboxes">
                <p>▢ Lack of Attendance / Late Coming</p>
                <p>▢ Poor Performance in CAT / Model Examination / University Examination</p>
                <p>▢ Indiscipline</p>
                <p>▢ Others Specify: </p>
            </div>
        </div>

        <div class="academic-performance">
            <b>2. Academic Performance:</b>
            <div>Attendance Percentage:
                <table class="attendance-table">
                    <tr>
                        <th>Slot 1</th>
                        <th>Slot 2</th>
                        <th>Slot 3</th>
                        <th>Slot 4</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td><?= htmlspecialchars($attendance_percentage) ?>%</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="marks-section">
            <b>3. CAT / Model Marks / University Examination:</b>
            <table class="marks-table">
                <tr>
                    <th>S. No.</th>
                    <th>Course code / Course Name</th>
                    <th>CAT 1</th>
                    <th>CAT 2</th>
                    <th>Model</th>
                    <th>University</th>
                </tr>
                <?php
                $s_no = 1;
                foreach ($grouped_marks as $subject_code => $exams): ?>
                    <tr>
                        <td><?= $s_no ?></td>
                        <td><?= htmlspecialchars($subject_code . ' / ' . $exams['NAME']) ?></td>
                        <td><?= htmlspecialchars($exams['CAT1'] ?? '') ?></td>
                        <td><?= htmlspecialchars($exams['CAT2'] ?? '') ?></td>
                        <td><?= htmlspecialchars($exams['MODEL'] ?? '') ?></td>
                        <td><?= htmlspecialchars($exams['UNIVERSITY'] ?? '') ?></td>
                    </tr>
                <?php
                $s_no++;
                endforeach;
                for ($i = $s_no; $i <= 6; $i++): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>

        <div class="arrears-section">
            <b>Total No. of Arrears:</b>
        </div>

        <div class="specified-reason-section">
            <b>4. Specified Reason:</b><br>
            <div class="specified-reason-row">
                <label class="specified-reason-label">Student</label>:
                <input type="text" class="specified-reason-input" value="    <?= htmlspecialchars($student_result['name']) ?>">
            </div>
            <div class="specified-reason-row">
                <label class="specified-reason-label">Parent’s / Guardian Name</label>:
                <input type="text" class="specified-reason-input">
            </div>
            <div class="specified-reason-row">
                <label class="specified-reason-label">Parent’s / Guardian Occupation</label>:
                <input type="text" class="specified-reason-input">
            </div>
            <div class="specified-reason-row">
                <label class="specified-reason-label">Parent’s / Guardian Comment</label>:
                <input type="text" class="specified-reason-input">
            </div>
        </div>

        <div class="counselor-comment-section">
            <b>5. Counsellor Comment:</b><br>
            <input type="text" class="specified-reason-input">
        </div>

        <div class="student-declaration-section">
            <b>6. Student Declaration:</b><br>
            <input type="text" class="specified-reason-input">
        </div>

        <div class="class-advisor-comment-section">
            <b>7. Class Advisor Comment:</b><br>
            <input type="text" class="specified-reason-input">
        </div>

        <div class="contact-numbers-section">
            <b>8. Contact Numbers:</b><br>
            <div class="contact-numbers-row">
                <label class="contact-label">Student</label>:
                <input type="text" class="specified-reason-input">
                <label class="signature-label">Signature</label>:
                <input type="text" class="specified-reason-input">
            </div>
            <div class="contact-numbers-row">
                <label class="contact-label">Parent’s / Guardian</label>:
                <input type="text" class="specified-reason-input">
                <label class="signature-label">Signature</label>:
                <input type="text" class="specified-reason-input">
            </div>
        </div>

        <div class="container">
        </div>
        <div class="signature-row-bottom">
            <div class="signature-col">
                <div class="signature-line">Counsellor</div>
            </div>
            <div class="signature-col">
                <div class="signature-line">Class Advisor</div>
            </div>
            <div class="signature-col">
                <div class="signature-line">HOD</div>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            window.print();
        }

        document.addEventListener('DOMContentLoaded', function() {
            printReport();
        });
    </script>
</body>
</html>
