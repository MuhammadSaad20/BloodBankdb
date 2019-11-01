<?php
// Initialize the session
// Initialize the session
require_once "config.php";
session_start();
$msg ="Select email from  cusers where id = '{$_SESSION['id']}' ";
$stmt=mysqli_query($connect,$msg);
$m1 ="Select location from  cusers where id = '{$_SESSION['id']}' ";
$s1=mysqli_query($connect,$m1);
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["select"] == 1){
    header("location: login.php");
    exit;
}


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to Blood Managment System Client Portal.</h1>
    </div>
    <p>
        <a href="resetpass.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
     </p>
     <h3><b><?php echo "Client User ID:   " .$_SESSION["id"];?></b></h3>
    <h3><b><?php echo "Username:   " .$_SESSION["username"];?></b></h3>
    <h3><b> <?php   
    echo "Email: ";
    while ($row = $stmt->fetch_assoc()) {
    echo $row['email']."<br>";
    }    ?></b></h3>
    <h3><b> <?php   
    echo "Location: ";
    while ($row = $s1->fetch_assoc()) {
    echo $row['location']."<br>";
}    ?></b></h3>
    
</body>
</html>