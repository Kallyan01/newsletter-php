<?php require "verifyOtp.php" ;?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="/view/css/global.css">
    <link rel="stylesheet" href="/view/css/otpverification.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Otp</title>
</head>
<body>



<form method="POST" action="verifyOtp.php" class="otp_form">

        <input type="text" name="action" id="otp_action" value="<?php if(!empty($_GET['action'])) echo $_GET["action"]; ?>">
        
        <input type="text" name="email" id="otp_email" value="<?php if(!empty($_GET['email'])) echo $_GET["email"]; ?>">
        
        <input type="number" name="otp" id="otp_value" required placeholder="Enter Your Otp">
        <input type="submit" value="Submit" name="submit" class="otp_submit">
    </form>

    </body>
</html>