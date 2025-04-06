<?php
include 'db_connection.php';

$branch = $_GET['branch'];
$year = $_GET['year'];
$section = $_GET['section'];
$exam = $_GET['exam'];

$query = "SELECT roll_no, reg_no, name, branch, year, section FROM students 
          WHERE branch = '$branch' AND year = '$year' AND section = '$section'";
$result = mysqli_query($conn, $query);

echo '<table border="1">
      <tr>
          <th>Roll No</th>
          <th>Reg No</th>
          <th>Name</th>
          <th>Branch</th>
          <th>Year</th>
          <th>Section</th>
      </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>
          <td>' . $row['roll_no'] . '</td>
          <td>' . $row['reg_no'] . '</td>
          <td>' . $row['name'] . '</td>
          <td>' . $row['branch'] . '</td>
          <td>' . $row['year'] . '</td>
          <td>' . $row['section'] . '</td>
          </tr>';
}

echo '</table>';
echo '<button id="generateReport">Generate Report</button>';

echo '<script>
        $("#generateReport").click(function() {
            window.location.href = "generate_univres.php?branch=' . $branch . '&year=' . $year . '&section=' . $section . '&exam=' . $exam . '";
        });
      </script>';
?>
