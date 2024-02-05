<?php

require '../config/database.php';

class OTPVerification {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sendVerificationEmail($email, $name) {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format");
        }

        // SQL injection prevention
        $email = $this->conn->real_escape_string($email);
        $name = $this->conn->real_escape_string($name);
        $otp = random_int(100000, 999999);
        echo $otp;
        // Insert data into database
        $sql = "INSERT INTO verification (email, name, otp) VALUES ('$email', '$name', '$otp')";

        if ($this->conn->query($sql) === TRUE) {
            $subject = "OTP Verification";
            $message = "Please Enter $otp to verify your subscription";
            $headers = "From: verification@github.com";

            // Send email
            $to = $email;
            if (mail($to, $subject, $message, $headers)) {
                echo "OTP successfully sent";
                header("Location: verifyOtpForm.php?action=subscribe&email={$email}");
            } else {
                echo "Failed to send email to: " . $to . "<br>";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }
}

// Usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST["email"]);
    $name = clean_input($_POST["name"]);

    // Create an instance of OTPVerification
    $otpVerification = new OTPVerification($conn);
    // Send verification email
    $otpVerification->sendVerificationEmail($email, $name);
}

// Function to clean input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
