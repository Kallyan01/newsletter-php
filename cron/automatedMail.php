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




// Initialize arrays to store successful and error email recipients
$successRecipients = [];
$errorRecipients = [];
 // Fetch and parse the XML content
// $xml_data = file_get_contents('https://github.com/timeline');
$xml_data = file_get_contents('http://demo.local/timeline.xml');
$xml = simplexml_load_string($xml_data);



    $subject = "GitHub XML Feed";
    $headers = "From: cron@example.com\r\n"; // Ensure proper header formatting
    $headers .= "MIME-Version: 1.0\r\n"; // Set MIME version
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; // Set content type to HTML

    class EmailTemplate{

        public function generateEmailBody($email,$xml)
        {
            $message = '<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>GitHub XML Feed</title>
    <style>

    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500&display=swap\');
  *
  {
  font-family: \'Poppins\', sans-serif;
  margin: 0;
  padding:0;
  }
  body
  {
  background-color :#232428;
  padding: .8rem;
  color: white;
  }
  a
  {
  color: yellow;
  }
  
      .card {
          border: 1px solid #ccc;
          margin: .8rem 0;
          padding: 10px;
          color: white;
          display: flex;
          flex-direction: column;
          border-radius: 3px;
      }
      h2 {
          
          font-weight: 400;
          font-size: 1rem;
      }
      p {
          
          display: inline;
          font-size: .8rem;
      }
      span p:nth-child(2)
      {
      font-size: .7rem;
      }
  
  .repolink
  {
  background-color: white;
  padding : .2rem .7rem;
  text-decoration: none;
  width: fit-content;
  color: black;
  border-radius: 3px;
  margin: 1rem 0 0 0;
  }
    
      </style>
    </head>
    <body>';
    if(!empty($xml))
    {
        foreach ($xml->entry as $item) {
            $title = (string)$item->title;
            $author_name = (string)$item->author->name;
            $url = (string) $item->link['href'];
            $date = (string)$item->published;
            $media_url = (string)$item->children('media', true)->thumbnail['url'];
            
            // Append entry HTML to the email message
            $message .= '<div class="card">';
            $message .= '<h2>Title: ' . $title . '</h2>';
            $message .= "<span><p>Published on </p> <p> {$date} </p></span>";
            $message .= "<span><p>By</p> <p>{$author_name}</p></span>";
            $message .=  "<a class=\"repolink\" href=\"{$url}\" _blank>Visit</a>";
            $message .= '</div>';
        }
    }

    $unsubscribe_link = "http://demo.local/unsubscribe.php?email=" . rawurlencode($email);
    $message .= '<p>To unsubscribe, click <a href="' . $unsubscribe_link . '">here</a>.</p>
    </body>
    </html>';
    return $message;
        }
    }
 

   

if ($result->num_rows > 0) {
    // Loop through each user and send email
    $result->data_seek(0); // Reset result pointer
    while ($row = $result->fetch_assoc()) {
        $to = $row["email"];
        $genEmail = new EmailTemplate();
        $message = $genEmail->generateEmailBody($to,$xml);
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
