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
include "engine/functions/core/class.chart.php";


$admin= new admin();
$admin->protect_page();

$room=new room();
$guest=new guest();
$chart = new Chart();


$WINDOW_NAME="Analytics";

?>

<html>
<head>
<?php include('includes/meta-main.php'); ?>	
	<title><?php echo($WINDOW_NAME); ?></title>
</head>
<body>
<?php include('includes/sidebar.php'); //sidebar ?>
<?php include('includes/topbar.php'); //topbar?>
<data PHPtoJS='currency_sign'><?php echo config::currency('sign'); ?></data>

<?php include 'includes/specifics/analytics_chart.php'; ?>



<div class="analytics-stats" align="center">
	<p class="month_conatiner"> Report for <?php echo date('F') ?> <?php echo date('Y') ?></p>
		<div class="inner-1" >
			<span class="item">
				<p>
					<h4 class="data-name"> Guest Registerations </h4>
					<h1 class="data"><?php echo stats::countRegThisMonth(date('m'),date('Y')); ?></h1>
				</p>
			</span>

			<span  class="item">
				<p>
					<h4 class="data-name"> Book Downs </h4>
					<h1 class="data"><?php echo stats::countBookDownThisMonth(date('m'),date('Y')); ?></h1>
				</p>
			</span>
			
			<span class="item">	
				<p>	
					<h4 class="data-name"> Gross Profit</h4>
					<h1 class="data"><?php echo config::currency('sign') ?> <?php echo number_format(finance::grossRegProfitOf(date('m'), date('Y'))) ?></h1>
				</p>
			</span>	

			<span class="item">	
				<p>	
					<h4 class="data-name"> Net Profit</h4>
					<h1 class="data"><?php echo config::currency('sign') ?> <?php echo number_format(finance::netRegProfitOf(date('m'), date('Y'))) ?></h1>
				</p>
			</span>						
		</div>
</div>

<div class="analytics-options" align="center">
	<form action="<? $_PHP_SELF ?>" method="GET">
		<select name="base">
			<option value="profits" <?php selectGetConst('base', 'profits') ?> >Profits</option>
			<option value="lodge_frequency" <?php selectGetConst('base', 'lodge_frequency') ?> >Lodge Frequency</option>
		</select>

		<select name="chart_context" id="for_profits">
			<option value="all_year" <?php selectGetConst('chart_context', 'all_year') ?> >All Year</option>
			<option value="quarter_1" <?php selectGetConst('chart_context', 'quarter_1') ?> > 1st Quarter</option>
			<option value="quarter_2" <?php selectGetConst('chart_context', 'quarter_2') ?> > 2nd Quarter</option>
			<option value="quarter_3" <?php selectGetConst('chart_context', 'quarter_3') ?> > 3rd Quarter</option>
			<option value="quarter_4" <?php selectGetConst('chart_context', 'quarter_4') ?> > 4th Quarter</option>
			<option value="half_1" <?php selectGetConst('chart_context', 'half_1') ?> > 1st 6 months</option>
			<option value="half_2" <?php selectGetConst('chart_context', 'half_2') ?> > 2nd 6 months</option>
		</select>	

		<select name="chart_context" id="for_lodge_frequency">
			<?php for ($i=1; $i<=12; $i++) : ?>
			<option value="<?php echo time_object::pad_zero_before_digit($i) ?>" <?php selectGetConst('chart_context', time_object::pad_zero_before_digit($i)) ?>> <?php echo time_object::integer_to_month($i) ?></option>
			<?php endfor ?>
		</select>

		<select name="year">
			<?php for($i=2018; $i<=2025; $i++): ?>
			<option value="<?php echo $i ?>" <?php selectGetConst('year', $i) ?>><?php echo $i ?></option>
			<?php endfor ?>
		</select>

		<select name="chart_type">
			<option value="bar" <?php selectGetConst('chart_type', 'bar') ?> >Bar</option>
			<option value="line" <?php selectGetConst('chart_type', 'line') ?> >Line</option>
		</select>				
		<input type="submit" name="track" value="Track">	
	</form>
</div>

<script src="/js/custom/main-lib.1.0.0.js"></script><!--Central Javascript Library -->
<script src="/js/chart/Chart.min.js"></script> <!-- Chart.js  -->
<?php include 'includes/specifics/analytics_chart_controller.php'; ?>



<script type="text/javascript">
	$ (function () {
		if( $("select[name=base] option:selected").val() =='profits') {
				$('#for_profits').attr("disabled", false);
				$("#for_profits").show();
				$('#for_lodge_frequency').attr("disabled", "disabled");
				$('#for_lodge_frequency').hide();
		}

		if( $("select[name=base] option:selected").val() =='lodge_frequency') {
				$('#for_lodge_frequency').attr("disabled", false);
				$("#for_lodge_frequency").show();
				$('#for_profits').attr("disabled", "disabled");
				$('#for_profits').hide();
		}		

		$("select[name=base]").on('change', function() {
			if ($(this).val()=='profits') {
				$('#for_profits').attr("disabled", false);
				$("#for_profits").show();
				$('#for_lodge_frequency').attr("disabled", "disabled");
				$('#for_lodge_frequency').hide();
			}
			if ($(this).val()=='lodge_frequency') {
				$('#for_lodge_frequency').attr("disabled", false);
				$("#for_lodge_frequency").show();
				$('#for_profits').attr("disabled", "disabled");
				$('#for_profits').hide();
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