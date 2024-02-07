<?php

// Connect to the database
$conn = new mysqli('localhost', 'root', 'root', 'local');

// Check database connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch users' email addresses from the database
$sql = "SELECT email FROM users";
$result = $conn->query($sql);


function getFullUrl() {
    // Check if HTTPS is set to "on" to determine the protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

    // Get the server name from SERVER_NAME
    $server_name = $_SERVER['SERVER_NAME'];

    // Get the port number from SERVER_PORT
    $port = $_SERVER['SERVER_PORT'];

    // Check if the port is not the default port for the protocol
    if (($protocol === 'http://' && $port != 80) || ($protocol === 'https://' && $port != 443)) {
        $port = ':' . $port; // Include the port in the URL
    } else {
        $port = ''; // No need to include the port if it's the default port
    }

    // Get the requested URI from REQUEST_URI
    $request_uri = $_SERVER['REQUEST_URI'];

    // Combine all the components to form the full URL
    $full_url = $protocol . $server_name . $port . $request_uri;

    // Return the full URL
    return $full_url;
}
$fullUrl = getFullUrl();

// Initialize arrays to store successful and error email recipients
$successRecipients = [];
$errorRecipients = [];

if ($result->num_rows > 0) {
    $subject = "GitHub XML Feed";
    $headers = "From: cron@example.com\r\n"; // Ensure proper header formatting
    $headers .= "MIME-Version: 1.0\r\n"; // Set MIME version
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; // Set content type to HTML
    
    // Start building the email message
    $message = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>GitHub XML Feed</title>
        <style>
            .entry {
                border: 1px solid #ccc;
                margin-bottom: 10px;
                padding: 10px;
            }
            h2 {
                margin-bottom: 5px;
            }
            p {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>';
    
    // Fetch and parse the XML content
    $xml_data = file_get_contents('https://github.com/timeline');
    $xml = simplexml_load_string($xml_data);
    
    // Loop through the XML elements and generate HTML for each entry
    foreach ($xml->entry as $item) {
        $title = (string)$item->title;
        $author_name = (string)$item->author->name;
        $media_url = (string)$item->children('media', true)->thumbnail['url'];
        
        // Append entry HTML to the email message
        $message .= '<div class="entry">';
        $message .= '<h2>Title: ' . $title . '</h2>';
        $message .= '<p>Author: ' . $author_name . '</p>';
        $message .= '</div>';
    }
    
    // Add unsubscribe link
    while ($row = $result->fetch_assoc()) {
        $to = $row["email"];
        $unsubscribe_link = "http://demo.local/unsubscribe.php?email=" . rawurlencode($to);
        $message .= '<p>To unsubscribe, click <a href="' . $unsubscribe_link . '">here</a>.</p>';
    }
    
    // Close HTML tags
    $message .= '</body>
    </html>';

    // Loop through each user and send email
    $result->data_seek(0); // Reset result pointer
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
