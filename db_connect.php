<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Modify these settings according to the account on your database server.
$host = "y0nkiij6humroewt.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$port = "3306";
$username = "i7x4rmulqury3hhs";
$user_pass = "pixi506s7ucq55yt";
$database_in_use = "vkxu7nat8ftr1op1"; // Ensure this matches exactly

$mysqli = new mysqli($host, $username, $user_pass, $database_in_use, $port);
if ($mysqli->connect_error) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
} else {
    echo "Connection successful: " . $mysqli->host_info . "<br>";
}
?>
