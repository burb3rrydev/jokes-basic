<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Modify these settings according to the account on your database server.
$host = "localhost";
$port = "3306";
$username = "root";
$user_pass = "root";
$database_in_use = "JokesDB"; // Ensure this matches exactly

$mysqli = new mysqli($host, $username, $user_pass, $database_in_use, $port);
if ($mysqli->connect_error) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
} else {
    echo "Connection successful: " . $mysqli->host_info . "<br>";
}
?>
