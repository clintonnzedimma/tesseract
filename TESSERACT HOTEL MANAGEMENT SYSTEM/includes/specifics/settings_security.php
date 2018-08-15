<?php 
if (submit_btn_clicked('edit-security')) {
	$username = sanitize_note($_POST['username']);
	$old_password = sanitize_note(grease($_POST['old_password']));
	$new_password = sanitize_note(grease($_POST['new_password']));
	$confirm_password = sanitize_note(grease($_POST['confirm_password']));

	if(strlen(trim($username))<3) {
		$errors[] = "Username must not be less than 3 characters"; 
	}
	if (check_for_whitespace($username)) {
		$errors[] = "Username should not contain white space";
	}
	if(empty($username)) {
		$errors[] = "Username must not be empty"; 
	}	


	if ($old_password!= $admin->get('password')) {
		$errors[] = "Wrong Password";
	}

	if (strlen(sanitize_note($_POST['new_password']))<4) {
		$errors[] = "Password must be 4 characters and above";
	}

	if ($new_password != $confirm_password) {
		$errors[] = "New Passwords do not match";
	}
}


?>




<center style="margin-top: 10px;">
	<div class="settings-security">
<?php 
			if (empty($errors)==true)
				{
					if(submit_btn_clicked('edit-security')) {
						config::updateSecurityDetails($_POST['username'], $_POST['new_password']);
						$success[] = "<b style='color:#15bd9c'>SECURITY SETTINGS SAVED SUCESSFULLY </b>";
						$SUCCESS_MESSAGE = error_msg($success);
						echo($SUCCESS_MESSAGE);
					}			
				}		

			else {
				$ERROR_MESSAGES = error_msg($errors);
				echo "<div align='center' style='color:red'>".$ERROR_MESSAGES." </div>";
			}
?>		
		<form id="SETTING-SECURITY-FORM" action="<? $_PHP_SELF ?>" method="POST">
			<span class="field_name">Username:</span><br>
			<input type="text" name="username">
			<br>
			<div class="error" id="username-ERROR"><br></div>
			<br>
			<span class="field_name">Password: </span> <br>
			<input type="password" name="old_password">
			<br>
			<div class="error" id="old_password-ERROR"><br></div>
			<br>
			<span class="field_name">New Password:</span><br>
			<input type="password" name="new_password">
			<br>
			<div class="error" id="new_password-ERROR"><br></div>
			<br>
			<span class="field_name">Confirm Password: </span><br>
			<input type="password" name="confirm_password">
			<br>
			<div class="error" id="confirm_password-ERROR"><br></div>
			<br>
			<input type="submit" name="edit-security" value="Change">
		</form>
	</div>
</center>

<data PHPtoJS="old_password_status"></data>


<script type="text/javascript">
$(function(){

$("input[name=username]").focusout(function(){
	checkUsername(this);
});

$("input[name=old_password]").focusout(function(){
	checkOldPasswordViaJSON(this);
});

$("input[name=new_password]").focusout(function(){
	checkNewPassword(this);
});

$("input[name=confirm_password]").focusout(function(){
	checkConfirmPassword(this);
});


function checkUsername (selector) {
	var	username = $(selector).val();
	if (username.trim().length<3 && username.trim().length>0) {
		$("#username-ERROR").html("Username should not be less than 3 characters");
		return true;
	} else if (checkForWhiteSpaceOf(username) && username.trim().length>3) {
		$("#username-ERROR").html("Username should not contain spaces");
		return true;		
	} else if (username.trim().length==0) {
		$("#username-ERROR").html("Username should not be empty");
		return true;		
	} else {
		$("#username-ERROR").html("<br>");
		return false;
	}
}

function checkNewPassword(selector) {
	var newPassword = $(selector).val();
	var oldPassword = $("input[name=old_password]").val();
	if (newPassword.trim().length<3) {
		$("#new_password-ERROR").html("Password should be 3 characters");
		return true;
	} else {
		$("#new_password-ERROR").html("<br>");
		return false;
	}
}

function checkConfirmPassword(selector) {
	var confirmPassword = $(selector).val();
	var newPassword = $("input[name=new_password]").val(); 
	if (confirmPassword!=newPassword && confirmPassword.trim().length>3) {
		$("#confirm_password-ERROR").html("Passwords do not match");
		return true;
	} else if (confirmPassword.trim().length==0) {
		$("#confirm_password-ERROR").html("Should not be empty");
		return true;
	}	else if (confirmPassword.trim().length<3) {
		$("#confirm_password-ERROR").html("Password again should be 3 characters");
		return true;
	}   else {
		$("#confirm_password-ERROR").html("<br>");
		return false;
	}
}

function checkOldPassword(selector) {
	var oldPassword = $(selector).val();
	var status =  $("[PHPtoJS=old_password_status]").html();

	if (status==0) {
		$("#old_password-ERROR").html("Wrong Password");
		return true;
	} else {
		$("#old_password-ERROR").html("<br>");
		return false;
	}

}

function checkOldPasswordViaJSON(selector) {
	var oldPassword = $(selector).val();
		$.getJSON(
		'widgets/json.data.php',
		'check_password='+oldPassword,
		function (data) {
			if (data.status==true) {
				$("[PHPtoJS=old_password_status]").html(1);
			} else {
				$("[PHPtoJS=old_password_status]").html(0);
			}
			});
	}
	

$("#SETTING-SECURITY-FORM").submit(function(e){
	checkUsername("input[name=username]");
	checkNewPassword("input[name=new_password]");
	checkConfirmPassword("input[name=confirm_password]");
	checkOldPasswordViaJSON("input[name=old_password]");
	checkOldPassword("input[name=old_password]");

	if(checkUsername("input[name=username]") || checkNewPassword("input[name=new_password]") || checkConfirmPassword("input[name=confirm_password]") || checkOldPassword("input[name=old_password]")) {
		e.preventDefault();
	}

});	

});


</script>


