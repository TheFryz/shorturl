<?php

// Get Config with Cred so no body steal login
include '/var/www/config/mysql.php';
// Connect to the MySQL database
$conn = mysqli_connect("localhost", $username, $password, "fryzurl");
// Check if the connection was successful
if (!$conn) {
    // If the connection fails, try to reestablish the connection
    $conn = mysqli_connect("localhost", $username, $password, "fryzurl");
    if (!$conn) {
        // If the connection still fails, display an error message and exit
        die("Connection failed: " . mysqli_connect_error());
    }
}
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the long URL from the form
    $long_url = mysqli_real_escape_string($conn, $_POST['long_url']);
    // Check if the long URL is already in the database
    $sql = "SELECT * FROM url_shortener WHERE long_url = '$long_url'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // If the long URL is in the database, get the short code for it
        $row = mysqli_fetch_assoc($result);
        $short_code = $row['short_code'];
        $short_url = "https://url.fryz.site/$short_code";
    } else {
        // If the long URL is not in the database, generate a unique short code for it
        $short_code = "";
        $alphabet = "abcdefghijklmnopqrstuvwxyz";
        do {
            $short_code = "";
            for ($i = 0; $i < 8; $i++) {
                $short_code .= $alphabet[rand(0, strlen($alphabet) - 1)];
            }
            // Check if the short code already exists in the database
            $sql = "SELECT * FROM url_shortener WHERE short_code = '$short_code'";
            $result = mysqli_query($conn, $sql);
        } while (mysqli_num_rows($result) > 0);
        // Insert the short code and long URL into the database
        $sql = "INSERT INTO url_shortener (short_code, long_url) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $short_code, $long_url);
        if (mysqli_stmt_execute($stmt)) {
            // If the insert was successful, display the short URL
            $short_url = "https://url.fryz.site/$short_code";
            //echo "Your short URL is: $short_url";
        } else {
            // If the insert failed, display an error message
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $short_url = "Error: Failed Inserting into mysql";
        }
    }
}
// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>

<html lang="en">

<head>

  <title>Fryz Short URL</title>

  <link rel="stylesheet" type="text/css" href=".\styles\index.css" />
  <link rel="stylesheet" type="text/css" href=".\styles\background.css" />
  <link rel="stylesheet" type="text/css" href=".\styles\preloader.css" />
  <link rel="icon" type="image/x-icon" href=".\images\fryz.ico">
  <meta name="description" content="Another short url service hosted by Fryz#9510">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css'>

</head>

<body>

<div id="preloader"></div>

  <div class="container">
    <div class="fryzbox">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" class="search-form">
        <input type="text" value="<?php echo $short_url; ?>" id="long_url" placeholder="Anything..." class="search-input" name="long_url">
        <button type="submit" class="search-button">
          <svg class="submit-button">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#search"></use>
          </svg>
        </button>
        <div class="search-option">
        </div>
      </form>
    </div>
	
	 <div class="bubbles">
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
  </div>
  </div>

  <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" display="none">
    <symbol id="search" viewBox="0 0 32 32">
      <path d="M 19.5 3 C 14.26514 3 10 7.2651394 10 12.5 C 10 14.749977 10.810825 16.807458 12.125 18.4375 L 3.28125 27.28125 L 4.71875 28.71875 L 13.5625 19.875 C 15.192542 21.189175 17.250023 22 19.5 22 C 24.73486 22 29 17.73486 29 12.5 C 29 7.2651394 24.73486 3 19.5 3 z M 19.5 5 C 23.65398 5 27 8.3460198 27 12.5 C 27 16.65398 23.65398 20 19.5 20 C 15.34602 20 12 16.65398 12 12.5 C 12 8.3460198 15.34602 5 19.5 5 z" />
    </symbol>
  </svg>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<script src=".\scripts\preloader.js"></script>
  <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  
</body>

</html>