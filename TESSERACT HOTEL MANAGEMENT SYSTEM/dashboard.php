<?php
ob_start();
session_start();
 ?>
<!DOCTYPE html>
<?php 
include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";
include "engine/functions/database/class.mainDB.php";
include "engine/functions/core/class.stats.php";
include "engine/functions/core/init.php";
include "engine/functions/core/errors.php";
include "engine/functions/core/class.time_object.php";
include "engine/functions/core/class.admin.php";
include "engine/functions/core/class.config.php";
include "engine/functions/core/class.room.php";
include "engine/functions/core/class.guest.php";
include "engine/functions/core/class.guest_singleton.php";

$admin= new admin();
$admin->protect_page();

$room=new room();

$guest=new guest();


$WINDOW_NAME="Dashboard";

?>

<html>
<head>
<?php include('includes/meta-main.php'); ?>	
	<title><?php echo($WINDOW_NAME); ?></title>
</head>
<body>
<?php include('includes/sidebar.php'); //sidebar ?>
<?php include('includes/topbar.php'); //topbar?>

<h1 align="center" style="color: #656565">Recent Guest List</h1>
<center>
	<div class="recent-guest-list" data-aos="fade-in" data-aos-duration="1000">
		<?php $guest->recentList(6); ?>
	</div>
</center>



<?php if(stats::countGuestsToBeCheckedOutToday()>0) : ?>
<h1 align="center" style="color: #656565">Due to be checked Out</h1>
<center>
	<div class="recent-guest-list recent-guest-list-check-out-today" data-aos="fade-in" data-aos-duration="1000">
		<?php $guest->recentListToBeCheckedOutToday(6); ?>
	</div>
	<a href="dashboard.php" class="action">Refresh</a>
</center>
<?php endif; ?>

<?php if(stats::countOverStayedGuestsNotCheckedOut()>0) : ?>
<h1 align="center" style="color: #656565">Over Stayed Guests</h1>
<center>
	<div class="recent-guest-list recent-guest-list-check-out-today" data-aos="fade-in" data-aos-duration="1000">
		<?php $guest->overStayedGuestsToBeCheckedOut(6); ?>
	</div>
	<a href="dashboard.php" class="action">Refresh</a>
</center>
<?php endif; ?>


<?php if(stats::countReservationsToBeCheckedInToday()>0) : ?>
<h1 align="center" style="color: #656565">Reservations to be checked in today</h1>
<center>
	<div class="recent-guest-list reservation-to-be-processed-today" data-aos="fade-in" data-aos-duration="1000">
		<?php $guest->reservationsToBeCheckedInToday(6); ?>
	</div>
	<a href="dashboard.php" class="action">Refresh</a>
</center>
<?php endif; ?>


<h1 align="center" style="color: #656565">Hotel Rooms</h1>
<center>
	<div class="dashboard-rooms" align="left">
<?php $room->displayAllByGrid(); ?>
	</div>
</center>

<?php 


?>



<script type="text/javascript">
    	// This javascript section activates the AOS library (aos.js) 
      AOS.init({
        easing: 'ease-in-out-sine'
      });
</script>
</body>
</html>