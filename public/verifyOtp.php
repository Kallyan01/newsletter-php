<?php

namespace Verification ;

require  "../config/database.php";

class OTPVerifier
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function verifyOTP($email, $user_otp, $user_action)
    {
        // Verify OTP
        $sql = "SELECT * FROM verification WHERE email = ? AND otp = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $user_otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // OTP is valid, fetch user data
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $email = $row['email'];

            if ($user_action === "subscribe") {
                // Insert user data into users table
                $insert_sql = "INSERT INTO users (name, email) VALUES (?, ?)";
                $stmt = $this->conn->prepare($insert_sql);
                $stmt->bind_param("ss", $name, $email);
                if ($stmt->execute()) {
                    echo "User data inserted successfully!";
                } else {
                    echo "Error inserting user data: " . $this->conn->error;
                }
            } else {
                // Delete user data from users table
                $delete_sql = "DELETE FROM users WHERE email = ?";
                $stmt = $this->conn->prepare($delete_sql);
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    echo "User deleted successfully!";
                } else {
                    echo "Error deleting user data: " . $this->conn->error;
                }
            }

            // Delete the OTP entry from verification table
            $delete_sql = "DELETE FROM verification WHERE email = ?";
            $stmt = $this->conn->prepare($delete_sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
        } else {
            // OTP is invalid
            echo "Invalid OTP";
        }
    }
}

// Usage
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['otp']) && !empty($_POST['email']) && !empty($_POST['action']) ) {
    // Retrieve input data
    $user_otp = $_POST['otp'];
    $user_action = $_POST['action'];
    $email = $_POST['email'];

    // Create an instance of OTPVerifier
    $otpVerifier = new OTPVerifier($conn);

    // Call the verifyOTP method
    $otpVerifier->verifyOTP($email, $user_otp, $user_action);
}
