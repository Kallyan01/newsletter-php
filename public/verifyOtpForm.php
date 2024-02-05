<?php require "verifyOtp.php" ;

echo "OTP verification";
?>


<form method="POST" action="verifyOtp.php">
        
        <label for="email">Email Id</label>
        <input type="text" name="email" id="">
        <label for="otp">OTP</label>
        <input type="number" name="otp" id="">
        <input type="submit" value="Submit" name="submit">
    </form>