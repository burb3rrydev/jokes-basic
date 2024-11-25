<?php
// Turn on error reporting for testing purposes. Delete these lines for production code.
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Keeps track of who is logged in.

// Include Google API Client
require_once('vendor/autoload.php');

// Google API client details
$client_id = "";
$client_secret = "";
$redirect_url = "http://localhost:8888/JOKES-BASICGOOGLE/google_login.php";

// MySQL details
$db_username = "root";
$db_password = "root";
$host_name = "localhost";
$db_name = "jokesdb";
$port = 3306;

// Google client setup
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_url);
$client->addScope("email");
$client->addScope("profile");
$service = new Google_Service_Oauth2($client);

// Handle different cases based on GET and session variables

// Case 1: Logout the user
if (isset($_GET['logout'])) {
    $client->revokeToken($_SESSION['access_token']);
    session_destroy();
    header('Location: index.php');
}

// Case 2: The URL contains a code from Google login service
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $go_here = filter_var($redirect_url, FILTER_SANITIZE_URL);
    header('Location: ' . $go_here);
    exit;
}

// Case 3: User is logged in. Retrieve user info.
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $authUrl = $client->createAuthUrl();
}

// Case 4: If the user is not logged in, display the login page
if (isset($authUrl)) {
    echo "<div align='center'><h1>Login</h1><p>You will need a Google account to use this login</p><a href='" . $authUrl . "'>Login Here</a></div>";
    exit;
}

// Case 5: User is logged in. Display data and add user to MySQL database.
$user = $service->userinfo->get(); // Get user info from Google

// Connect to MySQL
$mysqli = new mysqli($host_name, $db_username, $db_password, $db_name, $port);
if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Check if the user exists in the database
$stmt = $mysqli->prepare("SELECT user_id, google_id, google_name, google_email, google_picture_link FROM users WHERE google_id=?");
$stmt->bind_param("s", $user->id);  // This binds the google_id
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($userid, $google_id, $google_name, $google_email, $google_picture_link);


// If the user exists, display their details
if ($stmt->num_rows > 0) {
    echo "<h2>Welcome back " . $user->name . "!</h2>";
    echo "<p><a href='" . $redirect_url . "?logout=1'>Log Out</a></p>";
    echo "<p><a href='index.php'>Go to main page</a></p>";
    while ($stmt->fetch()) {
        echo "User Info:<br>";
        echo "UserID = " . $userid . "<br>";
        echo "Google Name = " . $google_name . "<br>";
        echo "Google Email = " . $google_email . "<br>";
    }
    // Save user session for other pages
    $_SESSION['username'] = $user->name;
    $_SESSION['googleuserid'] = $user->id;
    $_SESSION['useremail'] = $user->email;
    $_SESSION['userid'] = $userid;
} else {
    // New user, insert into database
    // New user, insert into database
echo "<h2>Welcome " . $user->name . " Thanks for Registering!</h2>";

// Ensure you include the user_name in the insert statement
$statement = $mysqli->prepare("INSERT INTO users (user_name, google_id, google_name, google_email, google_picture_link) VALUES (?, ?, ?, ?, ?)");

// Bind the parameters, including the user_name
$statement->bind_param('sssss', $user->name, $user->id, $user->name, $user->email, $user->picture);

// Execute the statement
$statement->execute();

// Get the ID of the newly inserted user
$newuserid = $statement->insert_id;


    // Display new user details
    echo "New user id = " . $newuserid . "<br>";
    echo "Google Name = " . $user->name . "<br>";
    echo "Google Email = " . $user->email . "<br>";
    echo "Google Picture = " . $user->picture . "<br>";

    // Save new user in session
    $_SESSION['userid'] = $newuserid;
    $_SESSION['username'] = $user->name;
    $_SESSION['googleuserid'] = $user->id;
    $_SESSION['useremail'] = $user->email;
}

// Display user profile
echo "<p>About this user</p>";
echo "<ul>";
echo "<img src='" . $user->picture . "' />";
echo "<li>Username: " . $user->name . "</li>";
echo "<li>User ID: " . $_SESSION['userid'] . "</li>";
echo "<li>User Email: " . $user->email . "</li>";
echo "</ul>";
echo "<p>Now go check the database to see if the new user has been inserted into the table.</p>";
echo "<a href ='index.php'>Return to the main page</a>";

echo "<br>Session values = <br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>

<style>
body { font-family: "helvetica"; }
img { height: 100px; }
</style>
