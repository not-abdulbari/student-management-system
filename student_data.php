<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'faculty/db_connect.php';

$student_data = [];
$student_data_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetch_student'])) {
    $roll_no = $_POST['roll_no'];

    // Fetch student details from the 'students' table
    $student_query = "SELECT * FROM students WHERE roll_no = '$roll_no'";
    $student_result = mysqli_query($conn, $student_query);

    if (mysqli_num_rows($student_result) > 0) {
        $student_data = mysqli_fetch_assoc($student_result);
    } else {
        $student_data_error = "No student found with the given roll number.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_student_info'])) {
    $roll_no = $_POST['roll_no'];
    $mail = $_POST['mail'];
    $dob = $_POST['dob'];
    $father_name = $_POST['father_name'];
    $occupation = $_POST['occupation'];
    $parent_phone = $_POST['parent_phone'];
    $student_phone = $_POST['student_phone'];
    $present_addr = $_POST['present_addr'];
    $permanent_addr = $_POST['permanent_addr'];
    $languages_known = $_POST['languages_known'];
    $school = $_POST['school'];
    $medium = $_POST['medium'];
    $math = $_POST['math'];
    $physic = $_POST['physic'];
    $chemis = $_POST['chemis'];
    $quota = $_POST['quota'];
    $cutoff = $math + $physic + $chemis;

    // Insert additional data into 'student_information' table
    $insert_query = "INSERT INTO student_information (roll_no, mail, dob, father_name, occupation, parent_phone, student_phone, present_addr, permanent_addr, languages_known, school, medium, math, physic, chemis, cutoff, quota) VALUES ('$roll_no', '$mail', '$dob', '$father_name', '$occupation', '$parent_phone', '$student_phone', '$present_addr', '$permanent_addr', '$languages_known', '$school', '$medium', '$math', '$physic', '$chemis', '$cutoff', '$quota')";
    
    if (mysqli_query($conn, $insert_query)) {
        echo "Student data successfully stored.";
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
    <title>Student Data Entry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 700px;
            width: 100%
            margin: auto;
            background: #fff;
            padding: 25px 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            border-radius: 8px;
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        label {
            margin: 10px 0 5px 10px;
            font-weight: bold;
            width : 40%;
        }
        input[type="text"], input[type="email"], input[type="number"], input[type="date"], select, textarea {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .table-container {
            margin-top: 20px;
            margin-right:50px;
            margin-left:50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .error {
            color: red;
            text-align: center;
        }
        .container form .user-details{
            display: grid;
            grid-template-columns: auto auto;
            justify-content: space-evenly;
            gap: 20px;
        }
        .user-details .input-box input{
            margin-top: 5px;
            height : 25px;
            width: 100%;
            border-radius : 0px 10px 0px 10px;
            width: 100%;
        }
        #roll_no{
            padding-left:30px;
        }
        textarea{
            margin-top: 5px;
            height : 25px;
            width: 100%;
            border-radius : 0px 10px 0px 10px;
            width: 100%;
        }
        .roll-num{
            padding-left: 45px;
            margin-top:10%
        }
        .fetch-btn{
            margin-left: 20px;
        }
        .add-info::before{
            content: '';
            position: absolute;
            top:86%;
            left:44.5%;
            height: 3px;
            width: 100px;
            background : green;
        }
        .stud::before{
            content: '';
            position: absolute;
            top:18.3%;
            left:45%;
            height: 4px;
            width: 160px;
            background : green;
    }
        
        /* Mobile view */
        @media only screen and (min-width : 360px) and (max-width : 670px) and (orientation : portrait){
            body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 700px;
            width: 100%
            margin: auto;
            background: #fff;
            padding: 25px 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 0px;
            border-radius: 8px;
            margin-left: 200px;
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        label {
            margin: 10px 0 5px 10px;
            font-weight: bold;
            width : 40%;
        }
        input[type="text"], input[type="email"], input[type="number"], input[type="date"], select, textarea {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .table-container {
            margin-top: 20px;
            margin-right:100px;
            margin-left:20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow:4px 4px 10px rgba(0,0,0,0.3);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .error {
            color: red;
            text-align: center;
        }
        .container form .user-details{
            display: block;
            justify-content: space-evenly;
            gap: 20px;
        }
        .user-details .input-box input{
            margin-top: 5px;
            height : 25px;
            width: 80%;
            border-radius : 10px 10px 10px 10px;
            margin-bottom:25px;
            border-color:green;
        }
        textarea{
            margin-top: 5px;
            height : 25px;
            width: 80%;
            border-radius : 10px 10px 10px 10px;
            margin-bottom:25px;
            border-color:green;
        }
        .roll-num{
            padding-left: 25px;
            margin-top:10%
        }
        .fetch-btn{
            margin:0px;
        }
        .add-info::before{
            content: '';
            position: absolute;
            top:100%;
            left:45%;
            height: 3px;
            width: 180px;
            background : green;
        }
        .add-info{
            margin-right:65px;
            font-size:24px;
        }
        .stud::before{
            content: '';
            position: absolute;
            top:15%;
            left:50%;
            height: 4px;
            width: 180px;
            background : green;
    }
    .stud{
        font-size:30px;
        padding-right:40px;
    }
        #quota{
        margin-top: 5px;
            height : 45px;
            width: 84%;
            border-radius : 10px 10px 10px 10px;
            margin-bottom:25px;
            border-color:green;
       }
       .h-captcha{
        margin-left:75px;
        margin-top:20px;
       }
       .submit-btn{
        margin-left:180px;
       }
       .std{
        margin-left:30px;
        margin-top: 55px;
        font-family: 'merriweather';
        font-size: 24px;
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.1);

       }
       #roll_no{
            padding-left:0px;
            margin-right:0px;
        }
        .fetch-btn{
            margin-right:30px;
        }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="font-family:'times new roman'" class="stud" class="stud1">Student Data Entry</h2>
        <form method="POST" action="student_data.php" style="margin-left:170px; margin-right:170px">
            <label for="roll_no" class="roll-num">Roll Number</label><br>
            <input type="text" name="roll_no" id="roll_no" required><br>
            <input type="submit" name="fetch_student" value="Fetch Student Details" class="fetch-btn"><br>
        </form>

        <?php if (!empty($student_data)): ?>
            <div class="table-container">
                <h3 class="std">Student Details</h3>
                <table>
                    <tr><th>Roll Number</th><td><?php echo htmlspecialchars($student_data['roll_no']); ?></td></tr>
                    <tr><th>Register Number</th><td><?php echo htmlspecialchars($student_data['reg_no']); ?></td></tr>
                    <tr><th>Name</th><td><?php echo htmlspecialchars($student_data['name']); ?></td></tr>
                    <tr><th>Branch</th><td><?php echo htmlspecialchars($student_data['branch']); ?></td></tr>
                    <tr><th>Year</th><td><?php echo htmlspecialchars($student_data['year']); ?></td></tr>
                    <tr><th>Section</th><td><?php echo htmlspecialchars($student_data['section']); ?></td></tr>
                </table>
            </div>

            <h3 style="margin-top:40px; margin-bottom:40px; font-family: 'merriweather';" class="add-info"><b>Additional Information</b></h3>
            <form method="POST" action="student_data.php" id="details">
                <input type="hidden" name="roll_no" value="<?php echo htmlspecialchars($student_data['roll_no']); ?>">
                <div class="user-details">
                <div class="input-box">
                <label for="mail">Mail</label><br>
                <input type="email" name="mail" id="mail" required><br>
                </div>
                <div class="input-box">
                <label for="dob">Date of Birth</label><br>
                <input type="date" name="dob" id="dob" required><br>
                </div>
                <div class="input-box">
                <label for="father_name">Father's Name</label><br>
                <input type="text" name="father_name" id="father_name" required><br>
                </div>
                <div class="input-box">
                <label for="occupation">Occupation</label><br>
                <input type="text" name="occupation" id="occupation" required><br>
                </div>
                <div class="input-box">
                <label for="parent_phone">Parent's Phone</label><br>
                <input type="text" name="parent_phone" id="parent_phone" required><br>
                </div>
                <div class="input-box">
                <label for="student_phone">Student's Phone</label><br>
                <input type="text" name="student_phone" id="student_phone" required><br>
                </div>
                <div class="input-box">
                <label for="present_addr">Present Address</label><br>
                <textarea name="present_addr" id="present_addr" required></textarea><br>
                </div>
                <div class="input-box">
                <label for="permanent_addr">Permanent Address</label><br>
                <textarea name="permanent_addr" id="permanent_addr" required></textarea><br>
                </div>
                <div class="input-box">
                <label for="languages_known">Languages Known</label><br>
                <input type="text" name="languages_known" id="languages_known" required><br>
                </div>
                <div class="input-box">
                <label for="school">School</label><br>
                <input type="text" name="school" id="school" required><br>
                </div>
                <div class="input-box">
                <label for="medium">Medium</label><br>
                <input type="text" name="medium" id="medium" required><br>
                </div>
                <div class="input-box">
                <label for="math">Math</label><br>
                <input type="number" name="math" id="math" required><br>
                </div>
                <div class="input-box">
                <label for="physic">Physics</label><br>
                <input type="number" name="physic" id="physic" required><br>
                </div>
                <div class="input-box">
                <label for="chemis">Chemistry</label><br>
                <input type="number" name="chemis" id="chemis" required><br>
                </div>
                <div class="input-box">
                <label for="quota">Quota</label><br>
                <select name="quota" id="quota" required>
                    <option value="management">Management</option>
                    <option value="counselling">Counselling</option>
                </select><br>
                </div>

                <div class="h-captcha" data-sitekey="your-hcaptcha-site-key"></div><br>
                <div class="submit-btn">
                <input type="submit" name="submit_student_info" value="Submit">
                </div>
                </div>
            </form>
        <?php elseif ($student_data_error): ?>
            <p class="error"><?php echo $student_data_error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
