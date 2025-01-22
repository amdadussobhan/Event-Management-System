<?php
// Database connection details
$host = 'localhost';     // Database host (usually localhost)
$user = 'root';           // Database username
$pass = 'Amdad@O11yo';               // Database password (leave blank if no password)
$db = 'event_management'; // Database name

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
