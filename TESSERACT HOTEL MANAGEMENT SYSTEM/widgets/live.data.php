<?php
ob_start();
session_start();
 ?>
<html id="live-data">

<head>
	<?php include 'meta.php'; ?>
</head>

<body>

<style type="text/css">
	@import url('../css/widgets.css');
	@import url('../css/main.css');
</style>
<?php
error_reporting (E_ERROR); // removing errors messages

include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";
include "../engine/functions/database/class.mainDB.php";
include "../engine/functions/core/init.php";
include "engine/functions/core/class.stats.php";
include "../engine/functions/core/class.finance.php";
include "../engine/functions/core/errors.php";
include "../engine/functions/core/class.time_object.php";
include "../engine/functions/core/class.admin.php";
include "../engine/functions/core/class.config.php";
include "../engine/functions/core/class.room.php";
include "../engine/functions/core/class.guest.php";
include "../engine/functions/core/class.guest_singleton.php";
include "../engine/functions/core/class.chart.php";


$admin= new admin();


$config= new config();


$room=new room();


$output='';

if (isset($_POST["Room_type_and_price"])) {
	$mainDB=new sqlite_DB("../engine/databases/main.DB");

	$room_id=sanitize_note($_POST["Room_type_and_price"]); 

	$sql="SELECT * FROM rooms WHERE id='$room_id'";
	$query=$mainDB->query($sql);
	$num_rows=$mainDB->numRows($query);

	if ($num_rows!=0) {
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$output.= "<div class='feedLive' data-aos='fade-down' data-aos-duration='20000'><strong> Room Type : </strong> <span id='type'>". $row['type']."</span><br> <strong> Room Price: ".$config->currency('sign')." </strong> <span id='price'>".number_format($row['price'])."</span></div>";
		}
	}
echo $output;
}

if (isset($_GET['chart_type']) && isset($_GET['from_month']) && isset($_GET['to_month'])) {
	$chart_type = $_GET['chart_type'];
	$from_month = $_GET['from_month'];
	$to_month = $_GET['to_month'];
###BREAKING OUT
?>
	<div align="center" style=" width: 70%; background: #fff; border:1px solid #e5e5e5; margin-top: 30px; box-shadow: #e5e5e5 1px 1px 1px 1px">
		<canvas id="lineChart"  style="color: #fff; height: 350px; ">
			
		</canvas>
	</div>
<?php 
###BREAKING IN
	$chart = new Chart();
	$chart->canvas_id = "lineChart";
	$chart->const = "CHART";
	$chart->type = 'line'; 
	$chart->declaration_test('2018');


}

?>


<script src="../js/chart/Chart.min.js"></script> <!-- Chart.js  -->
<script type="text/javascript">
    	// This javascript section activates the AOS library (aos.js) 
      AOS.init({
        easing: 'ease-in-out-sine'
      });
</script>

</body>

</html>