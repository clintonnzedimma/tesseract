<?php
ob_start();
 session_start();
 ?>
<!DOCTYPE html>
<?php 
include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";

include "engine/functions/core/init.php";
include "engine/functions/core/errors.php";
include "engine/functions/core/class.time_object.php";
include "engine/functions/core/class.guest.php";
include "engine/functions/core/class.admin.php";

$admin= new admin();

if ($admin->isLoggedIn()) {
	header("Location:dashboard.php?utm=sign_in");
}

?>
<html>
<head>
<?php include('includes/meta-main.php'); ?>	

	<title>TESSERACT</title>
</head>
<body class="global-1">
<?php
if (empty($_POST)==false) { 
$required_fields=array ('username', 'password'); //fields that must be field placed in an array
foreach ($_POST as $key => $value) {
	if (empty($value) && in_array($key, $required_fields) ===true) {
		$errors[]='Fill all fields';
		break 1;
	}

	}
} //REQUIRED FIELDS END HERE


if (submit_btn_clicked('log-in')) {
	$admin->LOGIN_INPUT_USERNAME=$_POST["username"];
	$admin->LOGIN_INPUT_PASSWORD=$_POST["password"];

	if(!$admin->check_login_details($admin->LOGIN_INPUT_USERNAME, $admin->LOGIN_INPUT_PASSWORD)) {
		$errors[]="Wrong Details";
	}
}




?>


<center style="margin-top: 15%" data-aos="fade-right" data-aos-duration="3000">
	<div class="index-logo-holder">
		<img src="css/img/logo.png" id="logo">
	</div>
	
	<div class="login-form-box" align="right">
		<?php
			if (empty($_POST)===false && empty($errors)===true)
				{
						$admin->LOGIN_INPUT_USERNAME=$_POST["username"];
						$admin->LOGIN_INPUT_PASSWORD=$_POST["password"];						
						$admin->session_auth();
						header("Location:dashboard.php");
						exit();
						
				}		

			else {
				$ERROR_MESSAGES= error_msg($errors);
				echo "<div align='center' class='error'>".$ERROR_MESSAGES." </div>";
			}			
		?>
		<form action="" method="POST">
			<input type="text" name="username" placeholder="Enter Username" autocomplete="off" required>
			<br>
			<input type="password" name="password" placeholder="Enter Password" required>
			<br>
			<input type="submit" name="log-in" value="Log In">
		</form>	
	</div>
</center>


<script type="text/javascript">
    	// This javascript section activates the AOS library (aos.js) 
      AOS.init({
        easing: 'ease-in-out-sine'
      });
</script>
</body>
</html>

<?php



?>

