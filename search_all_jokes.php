<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="utf-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <title>Jokes Accordion</title>  
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>  
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  

    <script>  
        $(function() {    
            $("#accordion").accordion();  
        });  
    </script>
</head>
<body>
    <h1>Jokes List</h1>
    <div id="accordion">
        <?php
            include "db_connect.php";
            
            // SQL query to get joke details along with the username of the user who created the joke
            $sql = "SELECT jokes_table.JokeID, jokes_table.Joke_question, jokes_table.Joke_answer, users.user_name 
                    FROM jokes_table 
                    JOIN users ON jokes_table.user_id = users.user_id";
                    
            // Prepare the statement
            $stmt = $mysqli->prepare($sql);
            
            // Execute the prepared statement
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {    
                // output data of each row    
                while($row = $result->fetch_assoc()) {        
                    echo "<h3>" . htmlspecialchars($row['Joke_question']) . "</h3>";        
                    echo "<div><p>" . htmlspecialchars($row['Joke_answer']) . " (submitted by: " . htmlspecialchars($row['user_name']) . ")</p></div>";    
                } 
            } else {    
                echo "<h3>No jokes found</h3><div><p>There are currently no jokes in the database.</p></div>";
            }

            // Close the statement
            $stmt->close();
        ?>
    </div>
</body>
</html>
