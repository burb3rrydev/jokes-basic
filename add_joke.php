<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {    
    echo "Only logged in users may access this page. Click <a href='login_form.php'>here</a> to login.<br>";    
    exit;
}

// Include database connection
include "db_connect.php";

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the form input (no need for addslashes)
    $new_joke_question = $_POST['newjoke'];
    $new_joke_answer = $_POST['jokeanswer'];
    
    // Retrieve the logged-in user's ID from session
    $userid = $_SESSION['userid'];
    
    echo "<h2>Trying to add a new joke: " . htmlspecialchars($new_joke_question) . " and " . htmlspecialchars($new_joke_answer) . "</h2>";
    
    // Prepare the SQL statement
    $stmt = $mysqli->prepare("INSERT INTO Jokes_table (Joke_question, Joke_answer, user_id) VALUES (?, ?, ?)");
    
    // Bind the parameters
    $stmt->bind_param("ssi", $new_joke_question, $new_joke_answer, $userid);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "<p>Joke added successfully!</p>";
    } else {
        echo "<p>Error adding joke: " . $stmt->error . "</p>";
    }
    
    // Close the prepared statement
    $stmt->close();
    
    // Redirect or include another page to show all jokes
    include "search_all_jokes.php";
    
    echo "<a href='index.php'>Return to main</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Joke</title>
</head>
<body>
    <h1>Add a New Joke</h1>
    <!-- Form for adding a new joke -->
    <form action="add_joke.php" method="POST">
        <div>
            <label for="newjoke">Joke Question:</label>
            <input type="text" id="newjoke" name="newjoke" required>
        </div>
        <div>
            <label for="jokeanswer">Joke Answer:</label>
            <input type="text" id="jokeanswer" name="jokeanswer" required>
        </div>
        <button type="submit">Add Joke</button>
    </form>
</body>
</html>
