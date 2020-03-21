<?php

if (isset($_POST["reset-request-submit"])) {
	
$selector = bin2hex(random_bytes(8));
$token = random_bytes(32);

$url = "http://localhost/login/forgottenpassword/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);

$expires = date("U") + 1800;

require 'config.php';

$userEmail = $_POST["email"];

$sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?;";
$stmt = mysqli_stmt_init($link);
if (!mysqli_stmt_prepare($stmt, $sql)) {
	echo "There was an error!";
	exit();
} else {
	mysqli_stmt_bind_param($stmt, "s", $userEmail);
	mysqli_stmt_execute($stmt);
}

$sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
$stmt = mysqli_stmt_init($link);
if (!mysqli_stmt_prepare($stmt, $sql)) {
	echo "There was an error!";
	exit();
} else {
	$hashedToken = password_hash($token, PASSWORD_DEFAULT);
	mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
	mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($link);

$to = $userEmail;

$subject = "rest your password for localhost";

$message = '<p>We recieved a password reset request. The link to reset your password is down below. If you did not make this request ignore this email.</P>';
$message .= '<p>Here is your password reset link: </br>';
$message .= '<a href="' .$url . '">' . $url . '</a></p>';

$headers = "From: localhost <donotreply@localhost.com>\r\n";
$headers .= "Reply-To: donotreply@localhost.com\r\n";
$headers .= "Content-type: text/html\r\n";

mail($to, $subject, $message, $headers);

header("Location: forgot_password.php?reset=success");


} else {
	header("Location: forgot_password.php?reset=fail");
}