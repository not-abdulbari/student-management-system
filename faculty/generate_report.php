<?php
include 'db_connect.php';

// Sanitize inputs
$branch = $conn->real_escape_string($_POST['branch']);
$year = $conn->real_escape_string($_POST['year']);
$section = $conn->real_escape_string($_POST['section']);
$semester = $conn->real_escape_string($_POST['semester']);
$subject = $conn->real_escape_string($_POST['subject']);
$exam = $conn->real_escape_string($_POST['exam']);

// Calculate statistics
$totalStudents = $conn->query(
    "SELECT COUNT(DISTINCT roll_no) FROM marks 
    WHERE branch='$branch' AND year='$year' 
    AND section='$section' AND semester='$semester'"
)->fetch_row()[0];

$result = $conn->query(
    "SELECT marks FROM marks 
    WHERE branch='$branch' AND year='$year' 
    AND section='$section' AND semester='$semester'
    AND subject='$subject' AND exam='$exam'"
);

$absent = 0;
$passed = 0;
$ranges = array_fill(0, 6, 0); // 91-100, 81-90,..., <50

while($row = $result->fetch_assoc()) {
    $mark = $row['marks'];
    
    if($mark == '-1') {
        $absent++;
        continue;
    }
    
    $numericMark = (int)$mark;
    
    if($numericMark >= 50) $passed++;
    
    if($numericMark >= 91) $ranges[0]++;
    elseif($numericMark >= 81) $ranges[1]++;
    elseif($numericMark >= 71) $ranges[2]++;
    elseif($numericMark >= 61) $ranges[3]++;
    elseif($numericMark >= 50) $ranges[4]++;
    else $ranges[5]++;
}

$appeared = $totalStudents - $absent;
$passPercentTotal = $totalStudents > 0 ? round(($passed / $totalStudents) * 100, 4) : 0;
$passPercentAppeared = $appeared > 0 ? round(($passed / $appeared) * 100, 4) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Result Analysis</title>
    <style>
        .back-button {
        background-color: #007BFF; /* Blue color */
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }
    .back-button:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }
        @media print {
            body { margin: 20px; font-family: Arial; }
            .no-print { display: none; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #000; padding: 5px; text-align: left; }
            .header { text-align: center; margin-bottom: 30px; }
             h3 { text-align: center; }
            .signatures { margin-top: 150px; display: flex; justify-content: space-between; }
        }
        @media screen {
            body { padding: 20px; }
            table { width: 80%; }
            .print-btn { margin: 20px; padding: 10px 20px; }
        }
    </style>
</head>
<body>
    <button onclick="goBack()" class="back-button">⬅ Back</button>
    <div class="header">
        <h2>C. ABDUL HAKEEM COLLEGE OF ENGINEERING & TECHNOLOGY</h2>
        <h3>MELVISHARAM - 632509</h3>
        <h4>DEPARTMENT OF                                      </h4>
    </div>

    <div class="report-data">
        <h3>INTERNAL EXAM RESULT ANALYSIS</h3>
        
        <form class="no-print" onsubmit="return finalizeReport(event)">
            <label>Subject Handler: <input type="text" id="handler" required></label>
            <label>Exam Date: <input type="date" id="examDate" required></label>
            <button type="submit" class="print-btn">Generate PDF</button>
        </form>

        <div id="printContent" style="display: none;">
            <p>Subject Handler: <span id="displayHandler"></span></p>
            <p>Exam Date: <span id="displayExamDate"></span></p>
            <p>Subject: <?= htmlspecialchars($subject) ?></p>
            <p>Year/Sem/Sec: <?= "$year/$semester/$section" ?></p>

            <table>
                <tr><th>Total Students</th><td><?= $totalStudents ?></td></tr>
                <tr><th>Appeared</th><td><?= $appeared ?></td></tr>
                <tr><th>Absent</th><td><?= $absent ?></td></tr>
                <tr><th>Passed</th><td><?= $passed ?></td></tr>
                <tr><th>Failed</th><td><?= $appeared - $passed ?></td></tr>
                <tr><th>Pass % (Total)</th><td><?= $passPercentTotal ?>%</td></tr>
                <tr><th>Pass % (Appeared)</th><td><?= $passPercentAppeared ?>%</td></tr>
            </table>

            <h4>Marks Distribution</h4>
            <table>
                <tr><th>Range</th><th>Students</th></tr>
                <?php
                $rangeLabels = ['91-100', '81-90', '71-80', '61-70', '51-60', '<50'];
                foreach($rangeLabels as $index => $label) {
                    echo "<tr><td>$label</td><td>{$ranges[$index]}</td></tr>";
                }
                ?>
            </table>

            <div class="signatures">
                <div>Faculty In-Charge: __________________</div>
                <div>HOD: __________________</div>
            </div>
        </div>
    </div>

    <script>
    function goBack() {
        window.history.back();
    }
    function finalizeReport(e) {
        e.preventDefault();
        
        // Update display values
        document.getElementById('displayHandler').textContent = 
            document.getElementById('handler').value;
        document.getElementById('displayExamDate').textContent = 
            document.getElementById('examDate').value;
        
        // Show printable content
        document.getElementById('printContent').style.display = 'block';
        
        // Trigger print after short delay
        setTimeout(() => {
            window.print();
            
            // Hide printable content after print
            setTimeout(() => {
                document.getElementById('printContent').style.display = 'none';
            }, 500);
        }, 100);
    }
    </script>
</body>
</html>
