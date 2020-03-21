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

 ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="gallery-container">
		<?php
		include 'includes/dbh.inc.php';

		$sql = "SELECT * FROM gallery ORDER BY orderGallery DESC";
		$stmt = mysqli_stmt_init($link);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed!";
		} else {
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			while ($row = mysqli_fetch_assoc($result)) {
				echo '<a href="#">
				<div style="background-image: url(images/gallery/'.$row["imgFullNameGallery"].');"></div>
				<h3>'.$row["titleGallery"].'</h3>
				<p>'.$row["descGallery"].'</p>
				</a>';
			}
		}
		
		?>
		<div>
			<?php
			if (isset($_SESSION['username'])) {
				echo '<div class="gallery-upload">
				<form action="includes/gallery-upload.inc.php" method="post" enctype="multipart/form-data">
					<input type="text" name="filename" placeholder="File Name...">
					<input type="text" name="filetitle" placeholder="Image title...">
					<input type="text" name="filedesc" placeholder="Image description...">
					<input type="file" name="file">
					<button type="submit" name="submit">Upload</button>
				</form>
			</div>';
			}
			
			?>
		</div>
	</div>
</body>
</html>