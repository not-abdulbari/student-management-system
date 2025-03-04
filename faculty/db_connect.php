<?php
    $host = 'localhost';
    $db = 'schooldb';
    $user = 'root';
    $pass = 'root';
    
    $conn = new mysqli($host, $user, $pass, $db);;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
    