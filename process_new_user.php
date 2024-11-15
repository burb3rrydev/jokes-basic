<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include "db_connect.php";

// Capture the form data using GET method
$new_username = $_GET['username'] ?? ''; // Use null coalescing to avoid undefined index warnings
$new_password1 = $_GET['password'] ?? '';
$new_password2 = $_GET['password-confirm'] ?? '';

// Basic input validation
if (empty($new_username) || empty($new_password1) || empty($new_password2)) {
    echo "Please fill in all fields.";
    exit;
}

// Check if the username already exists
$sql = "SELECT * FROM users WHERE user_name = ?";
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param("s", $new_username);
$stmt->execute();
$stmt->store_result();

// If username already exists
if ($stmt->num_rows > 0) {
    echo "The username " . htmlspecialchars($new_username) . " is already in use. Try another.";
    exit;
}

// Check if passwords match
if ($new_password1 !== $new_password2) {
    echo "The passwords do not match. Please try again.";
    exit;
}

// Enforce password rules using regex
// a. Passwords must contain at least one number
// b. Passwords must contain at least one special character
// c. Passwords must be at least 8 characters long
$password_pattern = '/^(?=.*[0-9])(?=.*[\W_]).{8,}$/';

if (!preg_match($password_pattern, $new_password1)) {
    echo "Password must be at least 8 characters long, contain at least one number, and at least one special character.";
    exit;
}

// Hash the password for security using password_hash
$hashed_password = password_hash($new_password1, PASSWORD_DEFAULT);

// Insert the new user into the database
$sql = "INSERT INTO users (user_name, password) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);

// Check if prepare was successful
if ($stmt === false) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param("ss", $new_username, $hashed_password);
$result = $stmt->execute();

if ($result) {
    echo "Registration successful!";
} else {
    echo "Something went wrong. Not registered. Error: " . mysqli_error($mysqli);
}

// Close the statement
$stmt->close();

// Provide a link to return to the main page
echo "<a href='index.php'>Return to main</a>";
?>
