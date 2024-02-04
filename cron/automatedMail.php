<?php

require_once '../config/database.php';

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