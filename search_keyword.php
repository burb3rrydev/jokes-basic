<?php
include "db_connect.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$keywordfromform = $_GET['keyword'];
echo "<h2>Show all jokes with the word '" . htmlspecialchars($keywordfromform) . "'</h2>";

// Prepare the SQL statement to prevent SQL injection
$query = "SELECT Jokes_table.JokeID, Jokes_table.Joke_question, Jokes_table.Joke_answer, users.user_name 
          FROM Jokes_table 
          JOIN users ON users.user_id = Jokes_table.user_id 
          WHERE Jokes_table.Joke_question LIKE ?"; 

// Prepare the statement
$stmt = $mysqli->prepare($query);

// Bind the parameter with the keyword (use wildcards for LIKE)
$searchTerm = "%" . $keywordfromform . "%";
$stmt->bind_param("s", $searchTerm);

// Execute the prepared statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {    
    echo "<div id='accordion'>";    
    while ($row = $result->fetch_assoc()) {        
        $safe_joke_question = htmlspecialchars($row['Joke_question']);        
        $safe_joke_answer = htmlspecialchars($row['Joke_answer']);        
        $safe_username = htmlspecialchars($row['user_name']);

        // Displaying the jokes and the username of the person who created them
        echo "<h3>" . $safe_joke_question . "</h3>";                
        echo "<div><p>" . $safe_joke_answer . " -- Submitted by user: " . $safe_username . "</p></div>";    
    }    
    echo "</div>";
} else {    
    echo "<p>No results found for the keyword.</p>";
}

// Close the statement
$stmt->close();
?>
