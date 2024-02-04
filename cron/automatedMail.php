<?php

define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

$conn = new mysqli(DB_HOST, DB_USER , DB_PASSWORD , DB_NAME);

if($conn->connect_error)
  die('Connection Failed');

// Fetch users' email addresses from the database
$sql = "SELECT email FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Email settings
    $subject = "Your Subject Here";
    $message = "Your Email Message Here";
    $headers = "From: your_email@example.com";
    
    // Loop through each user and send email
    while($row = $result->fetch_assoc()) {
        $to = $row["email"];
        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo "Email sent successfully to: " . $to . "<br>";
        } else {
            echo "Failed to send email to: " . $to . "<br>";
        }
    }
} else {
    echo "No users found in the database";
}

// Close connection
$conn->close();
?>