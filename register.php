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
$_SESSION['LAST_ACTIVITY'] = $time;

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username =  $firstname = $lastname = $email = $password = $confirm_password = "";
$username_err = $firstname_err = $lastname_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
	if(empty(trim($_POST["username"]))){
		$username_err = "Please enter a username.";
	} else{
        // Prepare a select statement
		$sql = "SELECT id FROM users WHERE username = ?";

		if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
			$param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				/* store result */
				mysqli_stmt_store_result($stmt);

				if(mysqli_stmt_num_rows($stmt) == 1){
					$username_err = "This username is already taken.";
				} else{
					$username = trim($_POST["username"]);
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}

            // Close statement
			mysqli_stmt_close($stmt);
		}
	}

	$input_firstname = trim($_POST["firstname"]);
	if(empty($input_firstname)) {
		$firstname_err = "Please enter your first name";
	} elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
		$firstname_err = "Please enter a valid first name.";
	} else {
		$firstname = $input_firstname;
	}

	$input_lastname = trim($_POST["lastname"]);
	if(empty($input_lastname)) {
		$lastname_err = "Please enter your last name";
	} elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
		$lastname_err = "Please enter a valid last name.";
	} else {
		$lastname = $input_lastname;
	}

	if(empty(trim($_POST["email"]))){
		$email_err = "Please enter an email.";
	} else{
        // Prepare a select statement
		$sql = "SELECT id FROM users WHERE email = ?";

		if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
			$param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				/* store result */
				mysqli_stmt_store_result($stmt);

				if(mysqli_stmt_num_rows($stmt) == 1){
					$email_err = "Please check your email. This email is already taken.";
				} else{
					$email = trim($_POST["email"]);
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}

            // Close statement
			mysqli_stmt_close($stmt);
		}
	}


    // Validate password
	$input_password = trim($_POST['password']);
	if(empty($input_password)){
		$password_err = "Please enter a password.";     
	} elseif(strlen(trim($_POST["password"])) < 8){
		$password_err = "Password must have atleast 8 characters.";
	} else{
		$password = $input_password;
	}

    // Validate confirm password
	if(empty(trim($_POST["confirm_password"]))){
		$confirm_password_err = "Please confirm password.";     
	} else{
		$confirm_password = trim($_POST["confirm_password"]);
		if(empty($password_err) && ($password != $confirm_password)){
			$confirm_password_err = "Password did not match.";
		}
	}



    // Check input errors before inserting in database
	if(empty($username_err) && empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
		$sql = "INSERT INTO users (username, firstname, lastname, email, password) VALUES (?, ?, ?, ?, ?)";

		if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_firstname, $param_lastname, $param_email, $param_password);

            // Set parameters
			$param_username = $username;
			$param_firstname = $firstname;
			$param_lastname = $lastname;
			$param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
            	header("location: login.php");
            } else{
            	echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
	<title>Sign Up</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="icon" href="images/mlk.ico" type="image/xicon">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<style type="text/css">
		html, body {
            height: 100%;
            background: #007bff;
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
            color: #ffffff;
        }
		.register{
			margin-top: 7px;
			padding: 3%;
		}
		.register-left{
			text-align: ;
			color: #fff;
			margin-top: 4%;
		}
		.register-left input{
			border: none;
			border-radius: 1.5rem;
			padding: 2%;
			width: 60%;
			background: #f8f9fa;
			font-weight: bold;
			color: #383d41;
			margin-top: 30%;
			margin-bottom: 3%;
			cursor: pointer;
		}
		.register-right{
			background: #f8f9fa;
			border-top-left-radius: 10% 50%;
			border-bottom-left-radius: 10% 50%;
		}
		.register-left img{
			margin-top: 15%;
			margin-bottom: 5%;
			width: 25%;
			-webkit-animation: mover 2s infinite  alternate;
			animation: mover 1s infinite  alternate;
		}
		@-webkit-keyframes mover {
			0% { transform: translateY(0); }
			100% { transform: translateY(-20px); }
		}
		@keyframes mover {
			0% { transform: translateY(0); }
			100% { transform: translateY(-20px); }
		}
		.register-left p{
			font-weight: lighter;
			padding-top: 12%;
			margin-top: -9%;
		}
		.register .register-form{
			padding: 10%;
			margin-top: 10%;
		}
		.btnRegister{
			float: right;
			margin-top: 10%;
			border: none;
			border-radius: 1.5rem;
			padding: 2%;
			background: #0062cc;
			color: #fff;
			font-weight: 600;
			width: 50%;
			cursor: pointer;
		}
		.register .nav-tabs{
			margin-top: 3%;
			border: none;
			background: #0062cc;
			border-radius: 1.5rem;
			width: 28%;
			float: right;
		}
		.register .nav-tabs .nav-link{
			padding: 2%;
			height: 34px;
			font-weight: 600;
			color: #fff;
			border-top-right-radius: 1.5rem;
			border-bottom-right-radius: 1.5rem;
		}
		.register .nav-tabs .nav-link:hover{
			border: none;
		}
		.register .nav-tabs .nav-link.active{
			width: 100px;
			color: #0062cc;
			border: 2px solid #0062cc;
			border-top-left-radius: 1.5rem;
			border-bottom-left-radius: 1.5rem;
		}
		.register-heading{
			text-align: center;
			margin-top: 8%;
			margin-bottom: -15%;
			color: #495057;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" style="padding: 0;">
		<a class="navbar-brand" href="home.php">
			<img src="images/mlk.png" height="35px">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="home.php"><i class="fas fa-home">Home</i></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="contact.php">Contact Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">About Us</a>
				</li>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link active" href="register.php"><i class="fas fa-user-alt">&nbsp;</i>Signup</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i>Login</a>
				</li>
			</ul>
		</div>
	</nav>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<div class="container register">
			<div class="row">
				<div class="col-md-3 register-left">
					<img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
					<h3>Welcome</h3>
					<p>You are 30 seconds away from having an account!</p>
				</div>
				<div class="col-md-9 register-right">
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
							<h3 class="register-heading">Sign up</h3>
							<div class="row register-form">
								<div class="col-md-6">
									<div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
										<input type="text" name="firstname" class="form-control" placeholder="First Name *" value="<?php echo $firstname; ?>" />
										<span class="badge badge-pill badge-danger"><?php echo $firstname_err; ?></span>
									</div>

									<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
										<input type="password" name="password" class="form-control" placeholder="Password *" value="<?php echo $password; ?>" />
										<span class="badge badge-pill badge-danger"><?php echo $password_err; ?></span>
									</div>
									<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
										<input type="email" name="email" class="form-control" placeholder="Email *" value="<?php echo $email; ?>" />
										<span class="badge badge-pill badge-danger"><?php echo $email_err; ?></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
										<input type="text" name="lastname" class="form-control" placeholder="Last Name *" value="<?php echo $lastname; ?>" />
										<span class="badge badge-pill badge-danger"><?php echo $lastname_err; ?></span>
									</div>
									<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
										<input type="password" name="confirm_password" class="form-control"  placeholder="Confirm Password *" value="<?php echo $confirm_password; ?>" />
										<span class="badge badge-pill badge-danger"><?php echo $confirm_password_err; ?></span>

									</div>
									<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
										<input type="text" class="form-control" name="username" placeholder="Username *" value="<?php echo $username; ?>" />
										<span class="badge badge-pill badge-danger"><?php echo $username_err; ?></span>
									</div>
									<div class="form-group">
										<input type="submit" class="btnRegister" value="Sign up"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</body>
</html>