<?php
ob_start();
session_start();
 ?>
<!DOCTYPE html>
<?php 
include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";
include "engine/functions/database/class.mainDB.php";
include "engine/functions/core/init.php";
include "engine/functions/core/errors.php";
include "engine/functions/core/class.time_object.php";
include "engine/functions/core/class.admin.php";
include "engine/functions/core/class.config.php";
include "engine/functions/core/class.room.php";
include "engine/functions/core/class.guest.php";
include "engine/functions/core/class.guest_singleton.php";
include "engine/functions/core/class.stats.php";


$admin= new admin();
$admin->protect_page();

$room=new room();

$guest=new guest();


$WINDOW_NAME="Guests Management";

if (!isset($_GET['lodged']) && !isset($_GET['checked_out']) && !isset($_GET['reservations']) ) {
	header("Location:general-management.php?lodged&p=1");
	exit();
	// redirect to ?lodged
}


?>

<html>
<head>
<?php include('includes/meta-main.php'); ?>	
	<title><?php echo($WINDOW_NAME); ?></title>
</head>
<body>
<?php include('includes/sidebar.php'); //sidebar ?>
<?php include('includes/topbar.php'); //topbar?>

<div class="general-management-nav" align="right">
	<a href="?lodged&p=1">Lodged Guests</a>
	<a href="?checked_out&p=1">Checked Out Guests</a>
	<a href="?reservations&p=1">Reservations</a>
</div>
<div class="general-manangement-search-form" align="right">
	<form action="search.php" method='GET'>
		<input type="text" name="search_query" value="<?php echo getConst('search_query'); ?>">
		<select name="db_tbl">
			<option value="guests" <?php  selectGetConst ('db_tbl', 'guests') ?> >Guests</option>
			<option value="reservations"  <?php  selectGetConst ('db_tbl', 'reservations') ?>>Reservations</option>
		</select>
		<input type="submit" value="search" name="submit-search">
		<br>
		<span class="error" id="search-error"> <br></span>
	</form>
</div>



<?php
if (isset($_GET['lodged']) && isset($_GET['p'])) {
### BREAKING OUT
?>
<!--?lodged Content Starts Here -->

<div class="general-management-main">
	<div class="general-management-sub-window-name">
		<h1 align="center">Lodged Guests</h1>
		<hr>
	</div>

	<div class="table">
		<?php $guest->get_page_num=sanitize_note($_GET['p']); ?>
		<?php $guest->file_name_of_page = "general-management.php?lodged&";  ?>
		<?php $guest->displayLodgedGuestsTable(10);  ?>
	</div>

</div>


<!--?lodged Content Ends Here  -->


<?php
}

#BREAKING IN



if (isset($_GET['checked_out']) && isset($_GET['p'])) {
	###BREAKING OUT
	?>
<!--?lodged Content Starts Here -->

<div class="general-management-main">
	<div class="general-management-sub-window-name">
		<h1 align="center">Checked Out Guests</h1>
		<hr>
	</div>

	<div class="table">
		<?php $guest->get_page_num=sanitize_note($_GET['p']); ?>
		<?php $guest->file_name_of_page = "general-management.php?checked_out&";  ?>		
		<?php $guest->displayCheckedOutGuestsTable(10);  ?>
	</div>
</div>

<!--?lodged Content Ends Here -->


	<?php
	###BREAKING IN
}


if (isset($_GET['reservations']) && isset($_GET['p'])) {
	###BREAKING OUT
	?>
<!--?reservations Content Starts Here -->

<div class="general-management-main">
	<div class="general-management-sub-window-name">
		<h1 align="center">Reservations</h1>
		<hr>
	</div>

	<div class="table">
		<?php $guest->get_page_num=sanitize_note($_GET['p']); ?>
		<?php $guest->file_name_of_page = "general-management.php?reservations&";  ?>		
		<?php $guest->displayReservationsTable(10);  ?>
	</div>

	

</div>


<!--?reserations Content Ends Here  -->
	<?php
	### BREAKING IN
}




?>


<script>
	$(function (){
			var search_input_field = $(".general-manangement-search-form input[name=search_query]"); // input form name
			var error_alert = $(".general-manangement-search-form span#search-error");	 // error alert span

			$(".general-manangement-search-form input[name=search_query]").focusout(function(){
				if ($(this).val().trim().length>0) {
					error_alert.html("<br>");
				}

			});

		$(".general-manangement-search-form input[name=submit-search]").on("click", function(e){
			/**
			* @event that when search button is clicked
			* If input field holds no value, the form will not be submitted
			*/
			if (search_input_field.val().trim().length==0) {
				e.preventDefault(); 
				error_alert.html("Field cannot be empty");
				error_alert.show();
			}
		});
	});
</script>



<script type="text/javascript">
    	// This javascript section activates the AOS library (aos.js) 
      AOS.init({
        easing: 'ease-in-out-sine'
      });
</script>

</body>
</html>