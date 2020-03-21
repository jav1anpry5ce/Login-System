<?php

require_once 'mconn.php';

$firstname = $lastname = $phone = $email = $message = "";
$firstname_err = $lastname_err = $phone_err = $email_err = $message_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$input_firstname = trim($_POST["FirstName"]);
	if(empty($input_firstname)){
		$firstname_err = "Please enter a First Name.";
	} elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
		$firstname_err = "Please enter a valid first name.";
	} else {
		$firstname = $input_firstname;
	}

	$input_lastname = trim($_POST["LastName"]);
	if(empty($input_lastname)){
		$lastname_err = "Please enter a Last Name.";
	} elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
		$lastname_err = "Please enter a valid last name.";
	} else{
		$lastname = $input_lastname;
	}

	$input_phone = trim($_POST["phone"]);
	if(empty($input_phone)) {
		$phone_err = "Please enter a phone number";
	} else {
		$phone = $input_phone;
	}

	$input_email = trim($_POST["email"]);
	if(empty($input_email)) {
		$email_err = "Please enter an email";
	} else {
		$email = $input_email;
	}

	$input_message = trim($_POST["message"]);
	if(empty($input_message)){
		$message_err = "Please enter a message";
	} else{
		$message = $input_message;
	}

	if(empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($message_err)) {
		$sql = "INSERT INTO messages (FirstName, LastName, phone, email, message) VALUES ('$firstname', '$lastname', '$phone', '$email', '$message');";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "sssss", $param_firstname, $param_lastname, $param_phone, $param_email, $param_message);

			$param_firstname = $firstname;
			$param_lastname = $lastname;
			$param_phone = $phone;
			$param_email = $email;
			$param_message = $message;

			if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
				header("location: contact.php?message=sent");
				exit();
			} else{
				header("Location: contact.php?message=fail");
				exit();
			}
		}
		mysqli_stmt_close($stmt);
	}
	mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="HandheldFriendly" content="true">
	<title>Contact Us</title>
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/mlk.ico"/>
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="style/css/util.css">
	<link rel="stylesheet" type="text/css" href="style/css/main.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<!--===============================================================================================-->
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
				<li class="nav-item active">
					<a class="nav-link" href="contact.php">Contact Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">About Us</a>
				</li>
			</ul>
			<form class="form-inline my-2 my-lg-0" action="https://www.google.com/search" method="GET" target="_blank"> 
				<input class="form-control mr-sm-2" type="search" placeholder="Google Search..." aria-label="Search" name="q" onfocus="value=''">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="register.php"><i class="fas fa-user-alt">&nbsp;</i>Signup</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i>Login</a>
				</li>
			</ul>
		</div>
	</nav>
	<?php
	if (isset($_GET["message"])) {
		if ($_GET["message"] == "sent") {
			?>
			<div class="alert alert-success alert-dismissible fade show" role="alert"> 
				<strong>Sucess!</strong> Your message was sent.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div> 
			<?php
		} elseif ($_GET["message"] == "fail") {
			?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Error!</strong> Something went wrong please try again later.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div> 
			<?php
		}
	}

	?>
	<div class="container-contact100">
		<div class="wrap-contact100">

			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="contact100-form validate-form" method="POST">

				<span class="contact100-form-title">
					Send Us A Message
				</span>

				<label class="label-input100" for="first-name">Tell us your name *</label>
				<div class="wrap-input100 rs1-wrap-input100 validate-input <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>" data-validate="Type first name">
					<input id="first-name" class="input100" type="text" name="FirstName" placeholder="First name" value="<?php echo $firstname; ?>">
					<span class="focus-input100"></span>
				</div>
				<div class="wrap-input100 rs2-wrap-input100 validate-input <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>" data-validate="Type last name">
					<input class="input100" type="text" name="LastName" placeholder="Last name" value="<?php echo $lastname; ?>">
					<span class="focus-input100"></span>
				</div>

				<label class="label-input100" for="email">Enter your email *</label>
				<div class="wrap-input100 validate-input <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" data-validate = "Valid email is required: ex@abc.xyz">
					<input id="email" class="input100" type="text" name="email" placeholder="Eg. example@email.com" value="<?php echo $email; ?>">
					<span class="focus-input100"></span>
				</div>

				<label class="label-input100" for="phone">Enter phone number</label>
				<div class="wrap-input100">
					<input id="phone" class="input100" type="text" name="phone" placeholder="Eg. +1 800 000000" value="<?php echo $phone; ?>">
					<span class="focus-input100"></span>
				</div>

				<label class="label-input100" for="message">Message *</label>
				<div class="wrap-input100 validate-input" data-validate = "Message is required">
					<textarea id="message" class="input100" name="message" placeholder="Write us a message"><?php echo $message; ?></textarea>
					<span class="focus-input100"></span>
				</div>

				<div class="container-contact100-form-btn">
					<button type="submit" class="contact100-form-btn">
						Send Message
					</button>
				</div>
			</form>



			<div class="contact100-more flex-col-c-m" style="background-image: url('images/images/bg-01.jpg');">
				<div class="flex-w size1 p-b-47">
					<div class="txt1 p-r-25">
						<span class="lnr lnr-map-marker"></span>
					</div>

					<div class="flex-col size2">
						<span class="txt1 p-b-20">
							Address
						</span>

						<span class="txt2">
							Mada Center 8th floor, 379 Hudson St, New York, NY 10018 US
						</span>
					</div>
				</div>

				<div class="dis-flex size1 p-b-47">
					<div class="txt1 p-r-25">
						<span class="lnr lnr-phone-handset"></span>
					</div>

					<div class="flex-col size2">
						<span class="txt1 p-b-20">
							Lets Talk
						</span>

						<span class="txt3">
							<a href="tel:+1 800 1236879">+1 800 1236879</a>
						</span>
					</div>
				</div>

				<div class="dis-flex size1 p-b-47">
					<div class="txt1 p-r-25">
						<span class="lnr lnr-envelope"></span>
					</div>

					<div class="flex-col size2">
						<span class="txt1 p-b-20">
							General Support
						</span>

						<span class="txt3">
							<a href="mailto:localhost@gmail.com">localhost@gmail.com</a>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div id="dropDownSelect1"></div>

	<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});
		window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove(); 
			});
		}, 3000);
	</script>
	<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-23581568-13');
	</script>
</body>
</html>
