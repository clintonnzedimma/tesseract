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
include "../../engine/functions/core/class.guest.php";
include "../../engine/functions/core/class.guest_singleton.php";

$admin= new admin();

$admin->protect_ModalWindow();

$config= new config();

$room=new room();

$guest_obj=new guest(); // general guest
$guest_obj->relateGuestsToReservations();



$config= new config();


$WINDOW_NAME="MANAGE GUEST";

$today=date('Y').'-'.date('m').'-'.date('d') ; // yyyy-mm-dd

$today_with_text_month=date("l").", ".date('d').'-'.date('M').'-'.date('Y');

$real_time=date('h').":".date("i").":".date("s")." ".date('a');


?>


<html class="<?php if(isset($_GET['guest_id'])) : ?> green-back <?php endif ?> <?php if(isset($_GET['reservation_id'])) : ?>blue-back<?php endif ?>">
<head>
	<title><?php echo $WINDOW_NAME; ?></title>
<?php include('modal-includes/meta.php'); ?>	
</head>

<body class="manage-guest">
	<?php 
if (isset($_GET['guest_id'])) {
	$id=sanitize_note($_GET['guest_id']); // guest database id
	$the_guest = new guest_singleton(['guest_id' => $id]);
		###BREAKING OUT
	?>
<!-- guest_id CONTENT STARTS HERE -->
<div class="top" align="center">
	<img src="../../css/img/blue-round-user.png" id="user-icon">
	<br>
	<span id="full_name">	<?php echo $the_guest->data["full_name"]; ?> </span>
</div>

<?php if ($the_guest->hasCheckedOut()){ ?><div class="check-out-status" align="center"> Guest Checked Out </div><?php } ?>

<div align="center">
	
</div>

<div class="guest-details" align="center">
	<p class="unit"><span class="field">Room:</span> <span class="guest-data"> <?php echo $the_guest->data["ROOM_NUMBER"] ?> (<?php echo $the_guest->data["ROOM_TYPE"] ?>) </span> </p>

	<p class="unit"><span class="field">Room Price per night:</span> <span class="guest-data"> <b id="naira-sign"><?php echo $config->currency('sign') ?></b> <?php echo number_format($the_guest->data["ROOM_PRICE"]) ?> </span> </p>

	<p class="unit"><span class="field">Checked In On:</span> <span class="guest-data"> <?php echo date('g:i a', strtotime($the_guest->data["CHECK_IN_TIME"])) ?>, <?php echo $the_guest->data["CHECK_IN_DATE"] ?></span> </p>

	<p class="unit"><span class="field"><?php if ($the_guest->checkedOutLate() || $the_guest->checkedOutEarly() || $the_guest->hasOverStayed()) { echo "Was to Check Out on:"; }  else { echo "Check Out on"; } ?></span> <span class="guest-data"><?php echo $the_guest->data["CHECK_OUT_DATE"] ?></span> </p>

	<?php if ($the_guest->checkedOutEarly()) : ?> <p class="unit"><span class="field">Checked Out Early:</span> <span class="guest-data"> <?php echo date('g:i a', strtotime($the_guest->data["CHECK_OUT_TIME"])) ?>, <?php echo $the_guest->data["EARLY_CHECK_OUT_DATE"] ?></span> </p> <?php endif; ?>

	<?php if ($the_guest->checkedOutLate()) : ?> <p class="unit"><span class="field">Checked Out Early:</span> <span class="guest-data"> <?php echo date('g:i a', strtotime($the_guest->data["CHECK_OUT_TIME"])) ?>, <?php echo $the_guest->data["LATE_CHECK_OUT_DATE"] ?></span> </p> <?php endif; ?>


	<?php if(!$the_guest->hasCheckedOut()):?><p class="unit"><span class="field">Duration of Stay:</span> <span class="guest-data">  <?php echo $the_guest->NUMBER_OF_DAYS_LODGED ;?> of <?php echo $the_guest->DURATION_TO_STAY ?> days <?php if ($the_guest->hasOverStayed ()) echo "(OVERSTAYED)"; ?></span> </p> <?php endif ?>


<?php if($the_guest->wasCheckedInViaReservation()): ?>	<p class="unit"><span class="field">Guest Reservation:</span> <span class="guest-data"><a href="javascript:window.open('manage-guest.php?reservation_id=<?php echo $the_guest->data['reservation_id']; ?>&mdl_src=manage-guest', '', 'width=800,height=700')">Reservation Data </a></span> </p>	<?php endif; ?>

	<p class="unit"><span class="field">Amount Deposited:</span> <span class="guest-data"> <b id="naira-sign"><?php echo $config->currency('sign') ?></b> <?php echo number_format($the_guest->data["amount_paid"]); ?></span> </p>

	<p class="unit"><span class="field">Refundable Amount:</span> <span class="guest-data"> <b id="naira-sign"><?php echo $config->currency('sign') ?></b> <?php echo number_format($the_guest->REFUNDABLE_AMOUNT); ?></span> </p>

	<p class="unit"><span class="field">Phone:</span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["phone"]) ?></span> </p>

	<p class="unit"><span class="field">Email: </span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["email"]); ?></span></p>

	<p class="unit"><span class="field">Sex:</span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["sex"]); ?></span> </p>

	<p class="unit"><span class="field">Home Address: </span> <span class="guest-data"> <?php echo hyphenateIfNull($the_guest->data['home_address']); ?></span> </p>

	<p class="unit"><span class="field">Occupation:</span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data['occupation']); ?> </span> </p>

	<p class="unit"><span class="field">Payment Type: </span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["payment_type"]); ?> </span> </p>
</div>

<div class="bottom" align="center">
	<?php  
		if (submit_btn_clicked('check-out')) {
			$the_guest->checkOut();
			$the_guest->room->setStatusById($the_guest->data["room_id"] , "VACANT");

			header("Location:manage-guest.php?guest_id=".$the_guest->data["id"]."");
			exit();			

		}

		if (submit_btn_clicked('undo-check-out')) {
			$the_guest->undoCheckOut();
			header("Location:manage-guest.php?guest_id=".$the_guest->data["id"]."");
			exit();
		}
	?>
	<form action="<?php $_PHP_SELF ?>" method="POST">
	<?php if (!$the_guest->hasCheckedOut()) { ?> <input type="submit" name="check-out" value="Check Out">  <?php } ?>
	<?php if ($the_guest->hasCheckedOut()) { ?> <input type="submit" name="undo-check-out" value="Undo Check Out">  <?php } ?>


		<input type="submit" name="refresh" value="Refresh">

	</form>
	<p style="color:#969988; font-size: 12px; font-family: verdana;">
		Refreshed as at <?php echo $real_time; ?>  |  <?php echo $today_with_text_month; ?>
	</p>

</div>
<!-- ?guest_id CONTENT ENDS HERE -->



	<?php
	###BREAKING IN
}


if (isset($_GET['reservation_id'])) {
	$id=sanitize_note($_GET['reservation_id']); // guest database id
	$the_guest = new guest_singleton(['reservation_id' => $id]);	
	###BREAKING OUT
	?>
<!-- ?reservation_id CONTENT STARTS HERE -->
<div class="top" align="center">
	<img src="../../css/img/blue-round-user.png" id="user-icon">
	<br>
	<span id="full_name">	<?php echo $the_guest->data["full_name"]; ?> </span>
</div>

<?php if(!$the_guest->reservationIsCheckedIn() && !$the_guest->reservationIsCancelled()) : ?> <div class="reservation-unprocessed " align="center">Reservation Pending</div> <?php endif ?>

<?php if($the_guest->reservationIsCheckedIn()) : ?> <div class="reservation-processed " align="center">Processed </div> <?php endif ?>

<?php if($the_guest->reservationIsCancelled()) : ?> <div class="reservation-cancelled" align="center">Cancelled</div> <?php endif ?>

<div align="center">
	
</div>

<div class="guest-details" align="center">
	<p class="unit"><span class="field">Room:</span> <span class="guest-data"> <?php echo $the_guest->data["ROOM_NUMBER"] ?> (<?php echo $the_guest->data["ROOM_TYPE"] ?>) </span> </p>

	<p class="unit"><span class="field">Room Price per night:</span> <span class="guest-data"> <b id="naira-sign"><?php echo $config->currency('sign') ?></b> <?php echo number_format($the_guest->data["ROOM_PRICE"]) ?> </span> </p>

	<p class="unit"><span class="field">To Check In On:</span> <span class="guest-data"> <?php echo $the_guest->data["CHECK_IN_DATE"] ?></span> </p>

	<p class="unit"><span class="field"> To Check Out on:</span> <span class="guest-data"><?php echo $the_guest->data["CHECK_OUT_DATE"] ?></span> </p>

	<p class="unit"><span class="field">Duration of Stay:</span> <span class="guest-data"> <?php echo $the_guest->DURATION_TO_STAY ?> <?php if ($the_guest->DURATION_TO_STAY>1) { echo"days";} else {echo"day";} ?> </span> </p>

	<p class="unit"><span class="field">Amount Deposited:</span> <span class="guest-data"> <b id="naira-sign"><?php echo $config->currency('sign') ?></b> <?php echo number_format($the_guest->data["amount_paid"]); ?></span> </p>

<?php if (!$the_guest->reservationIsCheckedIn()) : ?>	<p class="unit"><span class="field">Due:</span> <span class="guest-data"> <?php echo $the_guest->DUE_CHECK_IN_DAYS ?> <?php if ($the_guest->DUE_CHECK_IN_DAYS>1) { echo"days";} else {echo"day";} ?> </span> </p> <?php endif; ?>




	<p class="unit"><span class="field">Phone:</span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["phone"]) ?></span> </p>

	<p class="unit"><span class="field">Email: </span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["email"]); ?></span></p>

	<p class="unit"><span class="field">Sex:</span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["sex"]); ?></span> </p>

	<p class="unit"><span class="field">Home Address: </span> <span class="guest-data"> <?php echo hyphenateIfNull($the_guest->data['home_address']); ?></span> </p>

	<p class="unit"><span class="field">Occupation:</span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data['occupation']); ?> </span> </p>

	<p class="unit"><span class="field">Payment Type: </span> <span class="guest-data"><?php echo hyphenateIfNull($the_guest->data["payment_type"]); ?> </span> </p>

	<?php if ($the_guest->data["date_created"]): ?><p class="unit"><span class="field">Created: </span> <span class="guest-data"><?php echo time_object::manual_date_format($the_guest->data["date_created"],'/'); ?> </span> </p> <?php endif ?>
</div>


<div class="bottom" align="center">
	<?php 
		if (submit_btn_clicked('check-in')) {
			$the_guest->reservationCheckIn();						
			header("Location:manage-guest.php?reservation_id=".$the_guest->data["id"]."");
			exit();		
		}

		if (submit_btn_clicked('cancel')) {
			$the_guest->reservationCancel();
			header("Location:manage-guest.php?reservation_id=".$the_guest->data["id"]."");
			exit();				
		}

		if (submit_btn_clicked('undo-cancel')) {
			$the_guest->undoReservationCancel();
			header("Location:manage-guest.php?reservation_id=".$the_guest->data["id"]."");
			exit();				
		}
	?>
	<form action="<?php $_PHP_SELF ?>" method="POST">
	<?php if (!$the_guest->reservationIsCheckedIn() && $the_guest->reservationIsDue()) { ?> <input type="submit" name="check-in" value="Check In">  <?php } ?>
	<?php if (!$the_guest->reservationIsCheckedIn() && !$the_guest->reservationIsCancelled()) { ?> <input type="submit" name="cancel" value="Cancel">  <?php } ?>
	<?php if (!$the_guest->reservationIsCheckedIn() && $the_guest->reservationIsCancelled()) { ?> <input type="submit" name="undo-cancel" value="Undo Cancel">  <?php } ?>	
	<?php if ($the_guest->hasCheckedOut()) { ?> <input type="submit" name="undo-check-out" value="Undo Check In">  <?php } ?>


		<input type="submit" name="refresh" value="Refresh">

	</form>
	<p style="color:#969988; font-size: 12px; font-family: verdana;">
		Refreshed as at <?php echo $real_time; ?>  |  <?php echo $today_with_text_month; ?>
	</p>

</div>




<!-- ?reservation_id CONTENT ENDS HERE -->
	<?php
	### BREAKING IN
}


?>

</body>

</html>