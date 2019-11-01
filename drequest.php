<?php
// Include config file
require_once "config.php";
session_start();
// Define variables and initialize with empty values
 $location ="";
 $location_err = "";
 $did_fk="";

$avablity=$blood_group=$price=""; //
$avablity_err=$blood_group_err=$price_err="";

 $sql="";
$cnt=0;
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["select"] == 2){
    header("location: login.php");
    exit;
}
 
function built_error($cnt){
    if($cnt==1){
    echo "Something went wrong.You my left empty some fields or Please try again later.";
    }
    else{
        //do nothing
    }    
}
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    
    //validate location
    if(empty(trim($_POST["location"]))){
        $location_err = " Please enter a location."; //change here
    } else{
        // Prepare a select statement
        
            $sql = "SELECT rid FROM req WHERE location = ?";
        
        //$sql = "SELECT id FROM users WHERE location = ?";
        
        if($stmt = mysqli_prepare($connect, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_location);
            
            // Set parameters
            $param_location = trim($_POST["location"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                 
                    $location = trim($_POST["location"]);
                
            } else{
                echo "Please Enter location.";
            }
            mysqli_stmt_close($stmt);
        }

        else if($stmt==false){
            echo"location ";
            $cnt++; 
            built_error($cnt);
        }
        // Close statement
        
    }
    
        $a=$_SESSION["id"]; //stored fk value (banquet website client portal id do same thing saad!);
        
    
    // Validate password
    // Check input errors before inserting in database
    if(  empty($location_err)  ) {
        
        
        $sql = "INSERT INTO  req (did_fk,location) VALUES ($a,?)";
        
         
        if($stmt = mysqli_prepare($connect, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_location);
            
            // Set parameters
            $praam_location= $location;
            if(mysqli_stmt_execute($stmt)==true){
                // Redirect to login page
                
                
                header("location: reqview.php");
            } 
            else if(mysqli_stmt_execute($stmt)==false)
            {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
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
    <title>REq</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; color:black;}
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="page-header">
        <h1 align="center"> <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. It's good to see that you want to donate blood.</h1>
        <br>
        <h4 align="center">"Donate your blood for a reason, let the reason to be life"</h4>
    </div>
    <nav class="navbar navbar-expand-lg navbar-red bg-light">
  <a class="navbar-brand" href="#" >BLOOD BANK MANAGMENT SYSTEM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
    <div class="wrapper">
        <h3>Request Form</h3>
        <p>Please fill this form to post a request.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($location_err)) ? 'has-error' : ''; ?>">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo $location; ?>">
                <span class="help-block">Please fill location in which you available to donate blood<?php echo $location_err; ?></span>
            </div>

            <label>Chose Blood Group</label>
            
            <select class="custom-select" name="select">
            <option selected>Blood Group</option>
            <option value="A+">A+</option>
            <option value="O+">O+</option>
            <option value="B+">B+</option>
            <option value="AB+">AB+</option>
            <option value="A-">A-</option>
            <option value="O-">O-</option>
            <option value="B-">B-</option>
            <option value="AB-">AB-</option>
            </select>
            


            
            
            <hr>
                <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit Form">
                
            </div>
            
        </form>
    </div>    
</body>
</html>