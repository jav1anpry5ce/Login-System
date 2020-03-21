<?php

if (isset($_POST["reset-password-submit"])) {
	
$selector = $_POST["selector"];
$validator = $_POST["validator"];
$password = $_POST["pwd"];
$passwordRepear = $_POST["pwd-repeat"];

if (empty($password) || empty($passwordRepear)) {
		header("Location: forgot_password.php?empty");
		exit();
	} else if($password != $passwordRepear) {
		header("Location: forgot_password.php?error");
		exit();
	}

	$currentDate = date("U");

	require 'config.php';

	$sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ?";
	$stmt = mysqli_stmt_init($link);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "There was an error!";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);
		if (!$row = mysqli_fetch_assoc($result)) {
			echo "you need to re-submit your reset request";
			exit();
		}  else {

			$tokenBin = hex2bin($validator);
			$tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

			if ($tokenCheck === false) {
				echo "you need to re-submit your reset request";
				exit();
			} elseif ($tokenCheck === true) {

				$tokenEmail = $row['pwdResetEmail'];

				$sql = "SELECT * FROM users WHERE email=?;";
				$stmt = mysqli_stmt_init($link);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "There was an error!";
					exit();
				} else {
					mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					if (!$row = mysqli_fetch_assoc($result)) {
						echo "There was an error!";
						exit();
					}  else {

						$sql = "UPDATE users SET password=? WHERE email=?";

						$stmt = mysqli_stmt_init($link);
						if (!mysqli_stmt_prepare($stmt, $sql)) {
							echo "There was an error!";
							exit();
						} else {
							$newPwdHash = password_hash($password, PASSWORD_DEFAULT);
							mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
							mysqli_stmt_execute($stmt);

							$sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
							$stmt = mysqli_stmt_init($link);
							if (!mysqli_stmt_prepare($stmt, $sql)) {
								echo "There was an error!";
								exit();
							} else {
								mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
								mysqli_stmt_execute($stmt);
								header("Location: login.php?passwordupdated");
							}
						}
					}
				}

			}
		}
	}

} else{
	header("Location: login.php");
}