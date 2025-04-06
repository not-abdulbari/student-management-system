<!DOCTYPE html>
<html>
<head>
    <title>University Results</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h1>University Results</h1>
    <form id="resultsForm">
        <label for="branch">Branch:</label>
        <select id="branch" name="branch">
            <!-- Options will be populated from students.branch -->
        </select>
        <br>
        <label for="year">Year:</label>
        <select id="year" name="year">
            <!-- Options will be populated from students.year -->
        </select>
        <br>
        <label for="section">Section:</label>
        <select id="section" name="section">
            <!-- Options will be populated from students.section -->
        </select>
        <br>
        <label for="exam">Exam:</label>
        <select id="exam" name="exam">
            <!-- Options will be populated from university_results.exam via AJAX -->
        </select>
        <br>
        <button type="button" id="fetchStudents">Fetch Students</button>
    </form>
    <div id="studentList">
        <!-- Student list will be loaded here via AJAX -->
    </div>

    <script>
        $(document).ready(function() {
            // Populate branch, year, section dropdowns from students table
            $.ajax({
                url: 'get_student_filters.php',
                method: 'GET',
                success: function(data) {
                    var filters = JSON.parse(data);
                    $('#branch').html(filters.branchOptions);
                    $('#year').html(filters.yearOptions);
                    $('#section').html(filters.sectionOptions);
                }
            });

            // Populate exam dropdown from university_results table
            $.ajax({
                url: 'get_exams.php',
                method: 'GET',
                success: function(data) {
                    $('#exam').html(data);
                }
            });

            // Fetch students on button click
            $('#fetchStudents').click(function() {
                $.ajax({
                    url: 'university_studentlist.php',
                    method: 'GET',
                    data: $('#resultsForm').serialize(),
                    success: function(data) {
                        $('#studentList').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
