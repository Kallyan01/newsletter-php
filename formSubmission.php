

<?php

include "config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST["email"]);
    $name = clean_input($_POST["name"]);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // SQL injection prevention
    $email = $conn->real_escape_string($email);
    $name = $conn->real_escape_string($name);

    // Insert data into database
    $sql = "INSERT INTO users (email, name) VALUES ('$email', '$name')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to clean input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>