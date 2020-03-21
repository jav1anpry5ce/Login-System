<?php

session_start();

$time = $_SERVER['REQUEST_TIME'];
$timeout = 300;
 
 if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout) {
    session_unset();
    session_destroy();
    session_start(); 
}
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = $time;

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "conn.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM userdata WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $firstname = $row["FirstName"];
                $lastname = $row["LastName"];
                $email = $row["email"];
                $dob = $row["DOB"];
                $streetaddress = $row["StreetAddress"];
                $streetaddress2 = $row['StreetAddress2'];
                $city = $row["City"];
                $state = $row["State"];
                $zip = $row['Zip'];
                $country = $row["Country"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="icon" href="images/mlk.ico" type="image/xicon">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <p class="form-control-static"><?php echo $row["FirstName"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>Last Name</label>
                        <p class="form-control-static"><?php echo $row["LastName"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>Email</label>
                        <p class="form-control-static"><?php echo $row["email"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>DOB</label>
                        <p class="form-control-static"><?php echo $row["DOB"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>Street Address</label>
                        <p class="form-control-static"><?php echo $row["StreetAddress"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>Street Address 2</label>
                        <p class="form-control-static"><?php echo $row["StreetAddress2"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>City</label>
                        <p class="form-control-static"><?php echo $row["City"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>State</label>
                        <p class="form-control-static"><?php echo $row["State"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>Zip Code</label>
                        <p class="form-control-static"><?php echo $row["Zip"]; ?></p>
                    </div><hr>
                    <div class="form-group">
                        <label>Country</label>
                        <p class="form-control-static"><?php echo $row["Country"]; ?></p>
                    </div>
                    <p><a href="data.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>