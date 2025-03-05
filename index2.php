<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Counsellor's Book</title>
  <style>
 /* Basic Reset */
body {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    color: #333;
}

/* Header Section */
.header {
    position: relative;
    width: 100%;
    height: 250px;
    overflow: hidden;
}

.header img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.header .header-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    font-size: 20px;
    font-weight: bold;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
}

/* Banner Section */
.banner {
    background-color: #444;
    color: white;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.banner marquee {
    font-size: 16px;
    font-weight: bold;
}

@keyframes marquee {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

/* Main Container */
.main-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin: 20px 0;
    padding: 0 15px;
}

.container,
.notice_board {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    padding: 20px;
    margin: 10px;
    width: 30%;
}

h2 {
    font-size: 22px;
    color: #444;
    margin-bottom: 20px;
}

.btn {
    padding: 10px 20px;
    font-size: 14px;
    border: 1px solid #444;
    border-radius: 4px;
    background-color: transparent;
    color: #444;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
    width: 100%;
    max-width: 200px;
}

.btn:hover {
    background-color: #444;
    color: white;
}

/* Notice Board */
.notice_board marquee {
    font-size: 14px;
    color: #d9534f;
    font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
    .main-container {
        flex-direction: column;
        align-items: center;
    }

    .container,
    .notice_board {
        width: 90%;
    }

    .header img {
        height: 200px;
    }

    .header .header-content {
        font-size: 18px;
    }
}

  </style>
</head>
<body>

  <!-- Header Section with Image -->
  <div class="header">
    <img src="assets/logo.jpg" alt="Counsellor's Book Image"> <!-- Replace with your image path -->
  </div>

  <!-- Banner Section -->
  <div class="banner">
    <marquee behavior="scroll" direction="left">
      <p>Welcome to the Counsellor's Book System</p>
    </marquee>
  </div>

  <!-- Main Content Section -->
  <div class="main-container">
    <!-- Faculty Operations -->
    <div class="container">
      <h2>Institution login</h2>
      <a href='faculty/faculty_login.php'>
        <button class="btn">Login</button>
      </a> 
    </div>

    <!-- Students Operations -->
    <div class="container">
      <h2>Student login</h2>
      <a href='student/parent111.php'>
        <button class="btn">Login</button>
      </a> 
    </div>

    <!-- Notice Board -->
    <div class="notice_board">
      <h2>Notice Board</h2>
      <marquee behavior="scroll" direction="left">
        <p>Important: Faculty and Student Login Details are available on the portal.</p>
        <p>Note: The system will be down for maintenance from 2:00 AM to 4:00 AM tomorrow.</p>
        <p>Reminder: Mark your attendance before the deadline to avoid penalties.</p>
      </marquee>
    </div>
  </div>

</body>
</html>
