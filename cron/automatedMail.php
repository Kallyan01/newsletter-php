<?php


$conn = new mysqli('localhost', 'root' , 'root' , 'local');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch users' email addresses from the database
$sql = "SELECT email FROM users";
$result = $conn->query($sql);

// Initialize arrays to store successful and error email recipients
$successRecipients = [];
$errorRecipients = [];

if ($result->num_rows > 0) {
    $subject = "cron testing";
    $message = "email test cron";
    $headers = "From: cron@example.com";
    // Loop through each user and send email
    while ($row = $result->fetch_assoc()) {
        $to = $row["email"];
        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo "Email sent successfully to: " . $to . "<br>";
            $successRecipients[] = $to;
        } else {
            echo "Failed to send email to: " . $to . "<br>";
            $errorRecipients[] = $to;
        }
    }

    // Write successful and error email recipients to a text file
    $filename = 'email_log.txt';
    $fileContent = "Successful recipients:\n" . implode("\n", $successRecipients) . "\n\n";
    $fileContent .= "Error recipients:\n" . implode("\n", $errorRecipients) . "\n";

    file_put_contents($filename, $fileContent);
} else {
    echo "No users found in the database";
}

// Close database connection
$conn->close();
?>