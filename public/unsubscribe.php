<?php

namespace Unsubscribe;

require "../config/database.php";

class OTPHandler
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function generateAndSendOTP($email)
    {
        // Clean and validate email input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format");
        }

        // SQL injection prevention
        $email = $this->conn->real_escape_string($email);

        // Check if the user exists in the users table
        $sqlCheckUser = "SELECT * FROM users WHERE email = '$email'";
        $resultCheckUser = $this->conn->query($sqlCheckUser);

        if ($resultCheckUser->num_rows > 0) {
            // User exists, generate OTP
            $otp = random_int(100000, 999999);

            // Insert OTP data into the verification table
            $sqlInsertOTP = "INSERT INTO verification (email, name, otp) VALUES ('$email', '', '$otp')";
            $subject = "OTP Verification for unsubscribe";
            $message = "Please Enter $otp to verify you subscription ";
            $headers = "From: verification@github.com";
            $to = $email;
            if ($this->conn->query($sqlInsertOTP) === true && mail($to, $subject, $message, $headers)) {
                echo "OTP sent successfully!";
                header("Location: verifyOtpForm.php?action=unsubscribe&email={$email}");
            } else {
                echo "Error inserting OTP: " . $this->conn->error;
            }
        } else {
            echo "User does not exist";
        }
    }
}

// Usage
if ( !empty($_GET["email"])) {
    $email = $_GET["email"];

    // Create an instance of OTPHandler
    $otpHandler = new OTPHandler($conn);

    // Call the generateAndSendOTP method
    $otpHandler->generateAndSendOTP($email);
}
