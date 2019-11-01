<?php
// Initialize the session
session_start();
$select="";
$select_err="";
$sql="";
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($select == 1)
    { header("location: donor_dashboard.php");}
    else if($select == 2 )
    { header("location: client_dashboard.php"); }
    
    exit;   
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //check who's loged in
    if(empty(trim($_POST["select"]))){
        $select_err = "Please specified ypur role.";
    } else{
        $select = trim($_POST["select"]);
    }
    
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($select_err)){
        // Prepare a select statement
        if($select == 1)
        {$sql = "SELECT id, username, password FROM users WHERE username = ?";}
         if($select == 2)
        {$sql = "SELECT id, username, password FROM cusers WHERE username = ?";}
        $stmt = mysqli_prepare($connect, $sql);
        if($stmt == true ){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;   
                            $_SESSION["email"] = $email;
                            $_SESSION["location"] = $location;
                            $_SESSION["select"]= $select;
                            // Redirect user to welcome page
                            if($select==1)
                            {header("location: donor_dashboard.php");}
                            else if($select==2)
                            {header("location: client_dashboard.php");}
                        } 
                        else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        mysqli_stmt_close($stmt);
        }
        else if($stmt == false){
                echo "Oops! MOTHER FUCKER! Something went wrong. Please try again later.";
                
        }
        
        // Close statement
        
    }
    
    // Close connection
    mysqli_close($connect);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>BBMS| BLOOD BANK MANAGMENT SYSTEM</title>
 
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; color: black}
        .wrapper{ width: 350px; padding: 20px; color: black }
        
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-red bg-light">
  <a class="navbar-brand" href="#" >BLOOD BANK MANAGMENT SYSTEM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            
            <select class="custom-select" name="select">
            <option selected>Login As</option>
            <option value="1">Donor</option>
            <option value="2">Client</option>
            </select>
            <hr>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="nreg.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>