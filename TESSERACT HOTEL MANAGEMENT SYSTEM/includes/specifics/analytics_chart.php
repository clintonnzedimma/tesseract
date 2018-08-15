<?php 
/** 
* @author Clinton Nzedimma, Paul Princewill (c) Novacom Webs Nigeria 2018
* This is just a template for the main chart
* @isset of default is the chart when the user clicks the analytics on sidebar
* @isset of any other @param is dynamic
* Note that data here is controlled by analytics_chart_controller.php
*/ 
?>

<?php if (isset($_GET['default'])): ?>
<center>
	<div align="center" class="chart_holder">
		<canvas id="Chart_Container"  class="chart_itself"> </canvas>
	</div>
</center>	
<?php endif ?>

<?php if (isset($_GET['base']) && isset($_GET['chart_context']) && isset($_GET['year']) && isset($_GET['chart_type']) && isset($_GET['track'])): ?>
<center>
	<div align="center" class="chart_holder">
		<canvas id="Chart_Container"  class="chart_itself"> </canvas>
	</div>
</center>	
<?php endif ?>


