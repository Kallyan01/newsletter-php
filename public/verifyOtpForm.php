<?php require "verifyOtp.php" ;

echo "OTP verification";
?>


<form method="POST" action="verifyOtp.php">
<label for="action">Action</label>
        <input type="text" name="action" id="" value="<?php if(!empty($_GET['action'])) echo $_GET["action"]; ?>">
        <label for="email">Email Id</label>
        <input type="text" name="email" id="" value="<?php if(!empty($_GET['email'])) echo $_GET["email"]; ?>">
        <label for="otp">OTP</label>
        <input type="number" name="otp" id="">
        <input type="submit" value="Submit" name="submit">
    </form>