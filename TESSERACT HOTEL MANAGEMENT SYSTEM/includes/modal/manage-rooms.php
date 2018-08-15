<?php
ob_start();
 session_start();
 ?>
<!DOCTYPE html>
<?php 

include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";
include "../../engine/functions/database/class.mainDB.php";
include "../../engine/functions/core/init.php";
include "../../engine/functions/core/errors.php";
include "../../engine/functions/core/class.time_object.php";
include "../../engine/functions/core/class.admin.php";
include "../../engine/functions/core/class.config.php";
include "../../engine/functions/core/class.room.php";

$admin= new admin();

$admin->protect_ModalWindow();

$config= new config();

$room=new room();


$WINDOW_NAME="MANAGE ROOMS";

if(!isset($_GET['view']) && !isset($_GET['add']) && !isset($_GET['edit']) && !isset($_GET['delete'])) {
	header("Location:manage-rooms.php?view");
	exit();
}



?>


<html>
<head>
	<title><?php echo($WINDOW_NAME); ?></title>
<?php include('modal-includes/meta.php'); ?>	
</head>
<body>



<div class="modal-top-bar">
	<h1 align="center" class="window-name"><?php echo($WINDOW_NAME); ?></h1>
</div>
<div class="manage-rooms-nav">
	<a href="?view">View Rooms</a>
	<a href="?add">Add Rooms</a>
	<a href="?edit">Edit Rooms</a>
	<a href="?delete">Delete Rooms</a>
</div>

<div class="logo-watermark">
	<img src="../../css/img/logo.png"/>
</div> 

<?php
if (empty($_POST)==false) { 
$required_fields=array ('room_type', 'room_number', 'room_price'); //fields that must be have input values
foreach ($_POST as $key => $value) {
	if (empty($value) && in_array($key, $required_fields) ===true) {
		$errors[]='Fill all fields';
		break 1;
	}
	}
} //REQUIRED FIELDS END HERE




if (isset($_GET['view'])) {

?>

<center>
	<div class="view-rooms">
		<center>
			<div class="heading-text">
				<h1>VIEW ROOMS</h1>
			</div>
		</center>

		<div class="inner">
<?php $room->displayAllByTable();?>
		</div>

	</div>	
</center>

<?php	

}



if (isset($_GET['add'])) {
	// for add rooms content

	if(submit_btn_clicked("add-rooms-submit")) {
		$room_type=sanitize_note($_POST['room_type']);
		$room_number=sanitize_note($_POST['room_number']);
		$room_price=sanitize_note($_POST['room_price']);


		// ERRORS 
		if (!sanitize_integer($room_number)) {
			$errors[]="Please enter numeric Value for Room Number";
		}

		if (!sanitize_integer($room_price)) {
			$errors[]="Please enter numeric value for Room Price";
		}

		if ($room->numberExists($room_number)) {
			$errors[]="This room number <b>$room_number</b> already exists. Try another number";
		}


}

?>

<!-- ADD ROOM CONTENT STARTS HERE -->
<center> 

	<div class="add-rooms">
		<center>
			<div class="heading-text">
				<h1>ADD ROOM</h1>
			</div>
		</center>
<?php
		
			if (empty($_POST)===false && empty($errors)===true){
					
					$room->addNew($_POST['room_type'], $_POST['room_number'], $_POST['room_price']);

					//Success Messages
					$success[]="Room Added Successfully";
					$SUCCESS_MESSAGES=success_msg($success);
					echo "<div align='center' class='manage-rooms-SUCCESS'>$SUCCESS_MESSAGES </div>";

						
				} else {
					$ERROR_MESSAGES= error_msg($errors);
					echo "<div align='center' class='manage-rooms-ERRORS'>$ERROR_MESSAGES </div>";
				}		

?>
		<div class="inner">
			<form action=" <?php $_PHP_SELF?>" method="POST">
				<input type="text" placeholder="Room Type (e.g King Bedroom)" name="room_type" value ="<?php if(!empty($errors)) postConst('room_type') ?>" required>
				<br>
				 <input type="number" name="room_number" min="0" max="99999" placeholder="Room Number" value ="<?php if(!empty($errors))postConst('room_number') ?>" required>
				<b class="currency"><?php echo $config->currency('sign'); ?> </b>
				<input type="number" name="room_price" class="price" min="0" placeholder="Price" value ="<?php if(!empty($errors)) postConst('room_price') ?>"  required>
				<br>
				<input type="submit" name="add-rooms-submit">
			</form>
		</div>	
	</div>
</center>
<!-- ADD ROOM CONTENT ENDS HERE -->
















<?php	
}


if (isset($_GET['edit']) && empty($_GET['edit'])) {

	//content for view of editing rooms

?>
<center>
	<div class="view-rooms">
		<center>
			<div class="heading-text">
				<h1>EDIT ROOMS</h1>
			</div>
		</center>

		<div class="inner">
<!--Listing rooms for editing -->		
<?php $room->displayAllForEdit();?>
		</div>

	</div>	
</center>

<?php	
} else if (isset($_GET['edit']) && $room->numberExists($room->numberById($_GET['edit']))) {
		$get_room_number=sanitize_note($room->numberById($_GET['edit'])); //getting room rumber via id in url
		$get_room_id=sanitize_note($_GET['edit']); // room id PRIMARY KEY
	?>


<!-- EDIT BY ITS ID  ROOM CONTENT STARTS HERE. Please  the add-rooms class from @modal.css, it will be used to add and modify rooms -->
<center>
	<div class="add-rooms">
		<center>
			<div class="heading-text">
				<h1>EDIT ROOM  </h1>
			</div>
		</center>
<?php


			if (submit_btn_clicked('edit-rooms-submit')) {

				$room_type=sanitize_note($_POST['room_type']);
				$room_number=sanitize_note($_POST['room_number']);
				$room_price=sanitize_note($_POST['room_price']);				

				// ERRORS 
				if (!sanitize_integer($room_number)) {
					$errors[]="Please enter numeric value for Room Number";
				}

				if (!sanitize_integer($room_price)) {
					$errors[]="Please enter numeric value for Room Price";
				}

				if ($room->numberExists($room_number) && $room_number!=$get_room_number) {
						// if the number exists and its not the room number in the id
					$errors[]="This room number <b>$room_number</b> already exists. Try another number";
				}


			}
		 
			if (empty($_POST)===false && empty($errors)===true){
					
					$room->modifyById($get_room_id, $_POST['room_type'], $_POST['room_number'], $_POST['room_price']); // modifying room

					//Success Messages
					$success[]="Room Information Modified Successfully";
					$SUCCESS_MESSAGES=success_msg($success);
					echo "<div align='center' class='manage-rooms-SUCCESS'>$SUCCESS_MESSAGES </div>";

						
				} else {
					$ERROR_MESSAGES= error_msg($errors);
					echo "<div align='center' class='manage-rooms-ERRORS'>$ERROR_MESSAGES </div>";
				}		

?>
		<div class="inner">
			<form action=" <?php $_PHP_SELF?>" method="POST">
				<!--The value of each form is already set to get the database -->
				<input type="text" placeholder="Room Type (e.g King Bedroom)" name="room_type" value ="<?php if(!empty($errors)) {postConst('room_type');
				} else echo $room->getDataById($get_room_id,'type');?>" required>
				<br>
				 <input type="number" name="room_number" min="0" max="99999" placeholder="Room Number" value ="<?php if(!empty($errors)) {postConst('room_number');} else echo $room->getDataById($get_room_id,'number') ?>" required>
				<b class="currency"><?php echo $config->currency('sign'); ?> </b>
				<input type="number" name="room_price" class="price" min="0" placeholder="Price" value ="<?php if(!empty($errors)) {postConst('room_price');} else echo $room->getDataById($get_room_id,'price'); ?>"  required>
				<br>
				<input type="submit" name="edit-rooms-submit">
			</form>
		</div>	
	</div>
</center>
<!-- EDIT BY ITS ID  ROOM CONTENT ENDS HERE -->

	<?php
}





if (isset($_GET['delete'])) {
?>

<center>


	<div class="view-rooms">
		<center>
			<div class="heading-text">
				<h1>DELETE ROOMS</h1>
			</div>
		</center>
<?php 

if (submit_btn_clicked('room-delete-submit')) {
	foreach ($_POST as $room_id => $value) {
		if($room_id=="room-delete-submit") {
			// ignore button because its a not a room_id
		} else {
				// use checked values and ignore submit button 
			if ($room->numberById($room_id)) {
				//if room number of id exists, delete room by its id
				$room->deleteById($room_id);
				$success_status=true; //confirming delete


			} else {
					//refresh page in case of any error 
				header("Location:manage-rooms.php?delete");
				exit();
				break 1;

			}
		}
		
	}

	if ($success_status==true) {
		$success[]="Delete Successful";
		$SUCCESS_MESSAGES=success_msg($success);
		echo "<div align='center' class='manage-rooms-SUCCESS'>$SUCCESS_MESSAGES </div>";	
	}
} 

?>
		<div class="inner">
<?php $room->displayAllForDelete();?>
		</div>

	</div>	
</center>
<?php
}
?>





</body>
</html>