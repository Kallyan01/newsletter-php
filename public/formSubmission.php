

<?php

require '../config/database.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST["email"]);
    $name = clean_input($_POST["name"]);
     echo "hi";
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // SQL injection prevention
    $email = $conn->real_escape_string($email);
    $name = $conn->real_escape_string($name);
    $otp = random_int(100000, 999999);
    echo $otp;
    // Insert data into database
    $sql = "INSERT INTO verification (email, name , otp) VALUES ('$email', '$name' ,'$otp')";
    



    if ($conn->query($sql) === TRUE) {
        $subject = "OTP Verification ";
        $message = "Please Enter $otp to verify you subscription ";
        $headers = "From: verification@github.com";
        
        // Loop through each user and send email
       
            $to = $email;
            // Send email
            if (mail($to, $subject, $message, $headers)) {
                echo "OTP successfully Send";
                header("Location: verifyOtpForm.php");
            } else {
                echo "Failed to send email to: " . $to . "<br>";
            }
        
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