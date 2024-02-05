<?php
include "../config/database.php";
// Assume $user_otp is the OTP entered by the user



  

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $user_otp = $_POST['otp'];

    $email = $_POST['email'];
 
 
 
 // Verify OTP
 $sql = "SELECT * FROM verification WHERE email = '$email' AND otp = '$user_otp'";


    $result = $conn->query($sql);


if ($result->num_rows > 0) {
    // OTP is valid, fetch user data
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $email = $row['email'];

    // Insert user data into users table
    $insert_sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "User data inserted successfully!";
    } else {
        echo "Error inserting user data: " . $conn->error;
    }

    // Optionally, you may want to delete the OTP entry from otp_table
    $delete_sql = "DELETE FROM verification WHERE email = '$email'";
    $conn->query($delete_sql);
} else {
    // OTP is invalid
    echo "Invalid OTP";
}

// Close database connection
$conn->close();
}
?>
