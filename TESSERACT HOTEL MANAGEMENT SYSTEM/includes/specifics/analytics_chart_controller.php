<?php
/**
* @author Clinton Nzedimma (c) Novacom Webs Nigeria 2018
* The codes here are used to define parameters in the chart
* They control analytic_chart.php content
*/

if (isset($_GET['default'])) {
	$chart->canvas_id = "Chart_Container";
	$chart->const = "CHART";
	$chart->type = 'bar'; 
	$chart->profitsAllYear(date('Y'));
}
 ?>

<?php
if (isset($_GET['base']) && isset($_GET['chart_context']) && isset($_GET['year']) && isset($_GET['chart_type']) && isset($_GET['track'])) :

 ?>
<?php
	/**
	* @init variables to be used in this module
	*/
 	$base = $_GET['base'];
 	$chart_context = $_GET['chart_context'];
 	$year = $_GET['year'];
 	$get_type = $_GET['chart_type']; 
 	$track = $_GET['track'];

 	$context_confine = array('quarter_1', 'quarter_2', 'quarter_3', 'quarter_4', 'half_1', 'half_2');
 ?>
<?php
if($base == 'profits') {
	if($chart_context == 'all_year'){
		$chart->canvas_id = "Chart_Container";
		$chart->const = "CHART";
		$chart->type = $get_type; 
		$chart->profitsAllYear($year);
	}

	if (in_array($chart_context, $context_confine)) {
		$chart->canvas_id = "Chart_Container";
		$chart->const = "CHART";
		$chart->type = $get_type; 
		$chart->profitsOf($chart_context ,$year);
	}
}


if($base == 'lodge_frequency') {
	if($chart_context){
		$chart->canvas_id = "Chart_Container";
		$chart->const = "CHART";
		$chart->type = $get_type; 
		$chart->lodgeFreq($chart_context, $year);
	}



}
?>



<?php endif ?>