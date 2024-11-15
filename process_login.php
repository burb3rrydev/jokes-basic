<?php
session_start();
include "db_connect.php";

// Get the username and password from the POST request
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Use prepared statement to prevent SQL injection
$query = "SELECT user_id, user_name, password FROM users WHERE user_name = ?";

// Prepare the statement
$stmt = $mysqli->prepare($query);

// Bind the parameters to the prepared statement
$stmt->bind_param("s", $username); // Only bind the username, because password will be verified later

// Execute the prepared statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $user_name = $row['user_name'];
    $hashed_password = $row['password']; // Retrieve the hashed password from the database

    // Verify the password using password_verify()
    if (password_verify($password, $hashed_password)) {
        // Set session variables
        $_SESSION['username'] = $user_name;
        $_SESSION['userid'] = $user_id;

        echo "Login successful! Welcome, " . htmlspecialchars($user_name) . ".<br>";
        echo "<a href='index.php'>Go to home page</a>";
    } else {
        echo "Invalid username or password. <a href='login_form.php'>Try again</a>";
    }
} else {
    echo "Invalid username or password. <a href='login_form.php'>Try again</a>";
}

// Close the statement
$stmt->close();

// Close the database connection
$mysqli->close();
?>
