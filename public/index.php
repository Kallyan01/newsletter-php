<?php
include '../formSubmission.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="">
        <label for="email">Email Id</label>
        <input type="text" name="email" id="">
        <input type="submit" value="Submit">
    </form>
</body>
</html>