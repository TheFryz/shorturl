<?php

// Get Config with Cred so no body steal login
include '/var/www/config/mysql.php';

// Connect to the MySQL database
$conn = mysqli_connect("localhost", $username, $password, "fryzurl");

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the short code from the URL
$short_code = $_GET['code'];

if (empty($short_code)) {
    // If there is no code in the URL, redirect to the index page
    header("Location: /index.php");
    exit;
}

// Look up the long URL corresponding to the short code
$sql = "SELECT long_url FROM url_shortener WHERE short_code = '$short_code'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // If the short code was found, redirect to the long URL
    $row = mysqli_fetch_assoc($result);
    $long_url = $row['long_url'];
    header("Location: $long_url");
} else {
    // If the short code was not found, display a 404 error page
    header("HTTP/1.0 404 Not Found");
    echo "Error: 404 Page Not Found";
}

// Close the connection
mysqli_close($conn);

?>
