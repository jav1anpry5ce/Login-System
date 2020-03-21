<?php
	include_once 'conn.php';

	$first = $_POST['firstname'];
	$last = $_POST['lastname'];
	$email = $_POST['email'];
	$dob = $_POST['DOB'];
	$streetaddress = $_POST['streetaddress'];
	$streetaddress2 = $_POST['streetaddress2'];
	$city = $_POST['city'];
	$sp = $_POST['s/p'];
	$zip = $_POST['zip/postal'];
	$country = $_POST['country'];
        
    $sql = "INSERT INTO userdata (FirstName, LastName, email, DOB, StreetAddress, StreetAddress2, City, State, Zip, Country) VALUES ('$first', '$last', '$email', '$dob', '$streetaddress', '$streetaddress2', '$city', '$sp', '$zip', '$country');";
    mysqli_query($link, $sql);

    header("Location: data.php?datasubmitted=success");