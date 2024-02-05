<?php
include "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Clean and validate email input
    $email = clean_input($_GET["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // SQL injection prevention
    $email = $conn->real_escape_string($email);

    // Check if the user exists in the users table
    $sqlCheckUser = "SELECT * FROM users WHERE email = '$email'";
    $resultCheckUser = $conn->query($sqlCheckUser);
    
    if ($resultCheckUser->num_rows > 0) {
        // User exists, generate OTP
        $otp = random_int(100000, 999999);

        // Insert OTP data into the verification table
        $sqlInsertOTP = "INSERT INTO verification (email, name, otp) VALUES ('$email', '', '$otp')";
        $subject = "OTP Verification for unsubscribe";
        $message = "Please Enter $otp to verify you subscription ";
        $headers = "From: verification@github.com";
        $to = $email;
        if ($conn->query($sqlInsertOTP) === TRUE && mail($to, $subject, $message, $headers)) {
            echo "OTP sent successfully!";
            header("Location: verifyOtpForm.php?action=unsubscribe&email={$email}");
        } else {
            echo "Error inserting OTP: " . $conn->error;
        }
    } else {
        echo "User does not exist";
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
