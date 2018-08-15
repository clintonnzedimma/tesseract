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
include "engine/functions/core/class.admin.php";
include "engine/functions/core/class.config.php";
include "engine/functions/core/class.room.php";
include "engine/functions/core/class.guest.php";
include "engine/functions/core/class.guest_singleton.php";
include "engine/functions/core/class.stats.php";
$admin = new admin();

$room = new room ();
$guest = new guest();


$WINDOW_NAME = "TEST ENVIRONMENT";

?>

<!DOCTYPE html>
<html>
<head>
<?php include('includes/meta-main.php'); ?>	
</head>
<body>

<?php include('includes/sidebar.php'); //sidebar ?>
<?php include('includes/topbar.php'); //topbar?>



<?php 
		$hour=date('H'); // hour of post in 24 hour format
		$minute=date('i'); //minute 
		$second=date('s'); // second
echo "$hour : $minute <br>";




echo $admin->get('username');

 ?>

<button onclick="window.close();"> Close Window</button>

<button onclick="window.print();"> Print Page</button>


	<br>
	Lodge Frequency:
	<?php echo stats::countLodgeFreqOfRoomInYear(5,'2018'); ?>
	<br>

	<?php echo $guest->lastOne('full_name') ?>

	<br>


<?php

$guest= new guest();

echo "june->".finance::netRegProfitOf('06', '2018'). " july->". finance::netRegProfitOf('07', '2018')."<br>";
echo finance::netGrowthRateMonths('06-2018', '07-2018');

?>
%


	<div class="recent-guest-list">
	</div>

	<h1>
		<a href="javascript:window.open('widgets/json.data.php?room_id=1', '', 'width=1024,height=700')">Json by room id</a> 
		<br>
		<a href="javascript:window.open('widgets/json.data.php?stats', '', 'width=1024,height=700')">Stats</a> 
		<a href="javascript:window.open('widgets/json.data.php?check_password=qwerty3', '', 'width=1024,height=700')">Password JSON</a> 
	</h1>

<script src="js/custom/live-data.1.0.0.js"></script>
<!-- Getting Live Data from 'live.data.php' -->
<script src="/js/custom/main-lib.1.0.0.js"></script>
<!--Central Javascript Library -->

</body>
</html>

