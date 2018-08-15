<?php 
/**
*	@author Clinton Nzedimma, Bobby Nzedimma (c) Novacom Webs Nigeria
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Analytics
* This class contains properties and methods regarding charts
* This class is highly dependent on 'Guest', 'Room' and 'Sqlite_DB' class
* It will be used to describe Javascript properties with JQuery or core Javascript
*/


class Chart
{
	protected $mainDB;
	protected $guest_obj;
	protected $room;
	public $canvas_id;
	public $const;
	public $type;
	
	function __construct()
	{
		$this->mainDB = new Sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/main.DB");
		$this->guest_obj = new Guest();
		$this->room =  new Room();
	}

	public function declaration () {
		###BREAKING OUT
		?>
		<script type="text/javascript">
			const <?php echo($this->const) ?> = $("#<?php echo $this->canvas_id; ?>");
			var <?php echo($this->canvas_id) ?> = new Chart(<?php echo($this->const) ?>, {
				type: '<?php echo($this->type) ?>',
				//data below
			data: {
				labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
				datasets: [ 
					{
						label: 'My First dataset',
						backgroundColor: "rgba(144, 54, 145, 1)",
						borderColor: "rgba(144, 54, 145, 1)",
						data: [1,3,4,5,6,8,4],
						lineTension: 0.1,
						fill: false
					},  {
						label: 'My Second dataset',
						backgroundColor: "rgba(180, 214, 11, 1)",
						borderColor: "rgba(180, 214, 11, 1)",
						data: [6,2,1,9,8,2,5],
						lineTension: 0.1,
						fill: false
					},
				]
			}				
			});
		</script>		
		<?php
		###BREAKING IN
	}


function declaration_test ($param_year) {
		###BREAKING OUT
		?>
		<script type="text/javascript">
			var currencySign =  $("[PHPtoJS=currency_sign]").html();
			const <?php echo($this->const) ?> = $("#<?php echo $this->canvas_id; ?>");
			var <?php echo($this->canvas_id) ?> = new Chart(<?php echo($this->const) ?>, {
				type: '<?php echo($this->type) ?>',
				//data below
			data: {
				labels: [<?php  for ($i = intval($this->guest_obj->getById(1,'checkInMonth')); $i<=intval(date('m')); $i++) {
					if($i!=intval(date('m'))){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
				}
				} ?>],
				datasets: [ 
					{
						label: 'Gross Profit',
						backgroundColor: "rgba(90, 143, 177, 0.4)",
						borderColor: "rgba(90, 143, 177, 0.4)",
						fill:true,
						borderWidth:1.5,
						data: [<?php  for ($i = intval($this->guest_obj->getById(1,'checkInMonth')); $i<=intval(date('m')); $i++) {
					if($i!=intval(date('m'))){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
					}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
				}
				} ?>],
						
					},
					{
						label: 'Net Profit',
						backgroundColor: "rgba(182, 215, 13, 0.4)",
						borderColor: "rgba(182, 215, 13, 1)",
						fill: true,
						borderWidth:1.5,
						data: [<?php  for ($i = intval($this->guest_obj->getById(1,'checkInMonth')); $i<=intval(date('m')); $i++) {
					if($i!=intval(date('m'))){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
					}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
				}
				} ?>],
				
					}					
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				title: {
					display: true,
					text: 'Profits'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
					callbacks :  {
							label: function (tooltipItem, data) {
								var label = data.datasets[tooltipItem.datasetIndex].label || '';
								if (label) {
									label += ': ';
							}
								label+=tooltipItem.yLabel.toLocaleString();
								return label; 
						}
						}
					
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Months',
							fontColor:'#15bd9c'
						},
						ticks: {
						fontColor:'#999999',
						fontFamily:' "Baskerville Old Face"',
						fontSize:'13'
					}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value',
							fontColor:'#ec489c',
						},
						ticks: {
						callback: function(value, index, values) {
							if(parseInt(value)>=1000) {
							return currencySign+value.toLocaleString();
						} else if (parseInt(value)<=-1000) {
							return '- '+currencySign+Math.abs(value).toLocaleString();
						} else {
							return value;
					}
					},
						fontColor:'#4b4b4b',
						fontFamily: '"Avant Garde",Avantgarde,"Century Gothic",CenturyGothic,AppleGothic,sans-serif',
					}
					}]
				}
			}							
		});
		</script>		
		<?php

		###BREAKING IN
	}


function profitsAllYear ($param_year) {
	/**
	* @method is for profit of the param year
	*/
		###BREAKING OUT
		?>
		<script type="text/javascript">
			var currencySign =  $("[PHPtoJS=currency_sign]").html();
			const <?php echo($this->const) ?> = $("#<?php echo $this->canvas_id; ?>");
			var <?php echo($this->canvas_id) ?> = new Chart(<?php echo($this->const) ?>, {
				type: '<?php echo($this->type) ?>',
				//data below
			data: {
				labels: [<?php  for ($i = 1; $i<=12; $i++) {
					if($i!=12){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
				}
				} ?>],
				datasets: [ 
					{
						label: 'Gross Profit',
						backgroundColor: "rgba(90, 143, 177, 0.4)",
						borderColor: "rgba(90, 143, 177, 0.4)",
						fill:true,
						borderWidth:1.5,
						data: [<?php  for ($i = 1; $i<=12; $i++) {
					if($i!=12){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
					}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
				}
				} ?>],
						
					},
					{
						label: 'Net Profit',
						backgroundColor: "rgba(182, 215, 13, 0.4)",
						borderColor: "rgba(182, 215, 13, 1)",
						fill: true,
						borderWidth:1.5,
						data: [<?php  for ($i = 1; $i<=12; $i++) {
					if($i!=12){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
					}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
				}
				} ?>],
				
					}					
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				title: {
					display: true,
					text: 'Profits'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
					callbacks :  {
							label: function (tooltipItem, data) {
								var label = data.datasets[tooltipItem.datasetIndex].label || '';
								if (label) {
									label += ': ';
							}
								label+=tooltipItem.yLabel.toLocaleString();
								return label; 
						}
						}
					
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Months (<?php echo $param_year ?>)',
							fontColor:'#15bd9c'
						},
						ticks: {
						fontColor:'#999999',
						fontFamily:' "Baskerville Old Face"',
						fontSize:'13'
					}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value',
							fontColor:'#ec489c',
						},
						ticks: {
						callback: function(value, index, values) {
							if(parseInt(value)>=1000) {
							return currencySign+value.toLocaleString();
						} else if (parseInt(value)<=-1000) {
							return '- '+currencySign+Math.abs(value).toLocaleString();
						} else {
							return value;
					}
					},
						fontColor:'#4b4b4b',
						fontFamily: '"Avant Garde",Avantgarde,"Century Gothic",CenturyGothic,AppleGothic,sans-serif',
					}
					}]
				}
			}							
		});
		</script>		
		<?php

		###BREAKING IN
	}




function profitsOf($param_state, $param_year) {
		if ($param_state == 'quarter_1') {
			$label_attachment = $label_attachment = " 1st Quarter ($param_year)" ;
		} elseif ($param_state == 'quarter_2')  {
			$label_attachment = "2nd Quarter ($param_year)" ;
			 } elseif ($param_state == 'quarter_3')  { 
			 $label_attachment = 	" 3rd Quarter ($param_year)" ;
			 	}   
		 elseif ($param_state == 'quarter_4') { 
		 	$label_attachment = " 4th Quarter ($param_year)" ;
		 } elseif ($param_state == 'half_1')  { 
		 	$label_attachment = " 1st Half ($param_year) ";
		 }
		  elseif ($param_state == 'half_2') { 
		  $label_attachment = 	"2nd Half ($param_year) " ;
		}
		###BREAKING OUT
		?>
		<script type="text/javascript">
			var currencySign =  $("[PHPtoJS=currency_sign]").html();
			const <?php echo($this->const) ?> = $("#<?php echo $this->canvas_id; ?>");
			var <?php echo($this->canvas_id) ?> = new Chart(<?php echo($this->const) ?>, {
				type: '<?php echo($this->type) ?>',
				//data below
			data: {
				labels: [<?php  

			if ($param_state=='half_1'){					
					for ($i = 1; $i<=6; $i++) {
					if($i!=6){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
					}
				} 
			}

			if ($param_state=='half_2'){					
					for ($i = 7; $i<=12; $i++) {
					if($i!=12){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
					}
				} 
			}


			if ($param_state=='quarter_1'){					
					for ($i = 1; $i<=3; $i++) {
					if($i!=3){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
					}
				} 
			}

			if ($param_state=='quarter_2'){					
					for ($i = 4; $i<=6; $i++) {
					if($i!=6){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
					}
				} 
			}		

			if ($param_state=='quarter_3'){					
					for ($i = 7; $i<=9; $i++) {
					if($i!=9){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
					}
				} 
			}					


			if ($param_state=='quarter_4'){					
					for ($i = 10; $i<=12; $i++) {
					if($i!=12){
						echo "'".time_object::integer_to_month($i)."',";   
					}
				else {
					echo "'".time_object::integer_to_month("$i")."'";
					}
				} 
			}	
			?>],
				datasets: [ 
					{
						label: 'Gross Profit',
						backgroundColor: "rgba(90, 143, 177, 0.4)",
						borderColor: "rgba(90, 143, 177, 0.4)",
						fill:true,
						borderWidth:1.5,
						data: [<?php  
			if($param_state=='half_1'){						
					for ($i = 1; $i<=6; $i++) {
						if($i!=6){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='half_2'){						
					for ($i = 7; $i<=12; $i++) {
						if($i!=12){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
					}
				} 
			}


			if($param_state=='quarter_1'){						
					for ($i = 1; $i<=3; $i++) {
						if($i!=3){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='quarter_2'){						
					for ($i = 4; $i<=6; $i++) {
						if($i!=6){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='quarter_3'){						
					for ($i = 7; $i<=9; $i++) {
						if($i!=9){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='quarter_4'){						
					for ($i = 10; $i<=12; $i++) {
						if($i!=12){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::grossRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::grossRegProfitOf($month, $param_year);
					}
				} 
			}							

				?>],
						
					},
					{
						label: 'Net Profit',
						backgroundColor: "rgba(182, 215, 13, 0.4)",
						borderColor: "rgba(182, 215, 13, 1)",
						fill: true,
						borderWidth:1.5,
						data: [<?php 

			if($param_state=='half_1'){						
					for ($i = 1; $i<=6; $i++) {
						if($i!=6){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='half_2'){						
					for ($i = 7; $i<=12; $i++) {
						if($i!=12){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
					}
				} 
			}
			

			if($param_state=='quarter_1'){						
					for ($i = 1; $i<=3; $i++) {
						if($i!=3){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='quarter_2'){						
					for ($i = 4; $i<=6; $i++) {
						if($i!=6){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='quarter_3'){						
					for ($i = 7; $i<=9; $i++) {
						if($i!=9){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
					}
				} 
			}

			if($param_state=='quarter_4'){						
					for ($i = 10; $i<=12; $i++) {
						if($i!=12){
						$month = time_object::pad_zero_before_digit($i);
						echo finance::netRegProfitOf($month, $param_year).",";   
						}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo finance::netRegProfitOf($month, $param_year);
					}
				} 
			}			 ?>],
				
					}					
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				title: {
					display: true,
					text: 'Profits'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
					callbacks :  {
							label: function (tooltipItem, data) {
								var label = data.datasets[tooltipItem.datasetIndex].label || '';
								if (label) {
									label += ': ';
							}
								label+=tooltipItem.yLabel.toLocaleString();
								return label; 
						}
						}
					
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Months - <?php echo $label_attachment ?>',
							fontColor:'#15bd9c'
						},
						ticks: {
						fontColor:'#999999',
						fontFamily:' "Baskerville Old Face"',
						fontSize:'13'
					}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value',
							fontColor:'#ec489c',
						},
						ticks: {
						callback: function(value, index, values) {
							if(parseInt(value)>=1000) {
							return currencySign+value.toLocaleString();
						} else if (parseInt(value)<=-1000) {
							return '- '+currencySign+Math.abs(value).toLocaleString();
						} else {
							return value;
					}
					},
						fontColor:'#4b4b4b',
						fontFamily: '"Avant Garde",Avantgarde,"Century Gothic",CenturyGothic,AppleGothic,sans-serif',
					}
					}]
				}
			}							
		});
		</script>		
		<?php

		###BREAKING IN
	}




function lodgeFreq ($param_month ,$param_year) {
		###BREAKING OUT
		?>
		<script type="text/javascript">
			var currencySign =  $("[PHPtoJS=currency_sign]").html();
			const <?php echo($this->const) ?> = $("#<?php echo $this->canvas_id; ?>");
			var <?php echo($this->canvas_id) ?> = new Chart(<?php echo($this->const) ?>, {
				type: '<?php echo($this->type) ?>',
				//data below
			data: {
				labels: [ <?php  
				for ($i = 1; $i<=stats::countRooms(); $i++) {
					if($i!=stats::countRooms()){
						echo $this->room->getDataById($i, 'number').", ";
					}
				else {
					echo $this->room->getDataById($i, 'number');
				}
				} ?>],
				datasets: [ 
					{
						label: 'Frequency',
						backgroundColor: "rgba(238, 78, 27, 0.4)",
						borderColor: "rgba(238, 78, 27, 0.4)",
						fill:true,
						borderWidth:1.5,
						data: [<?php  
				for ($i = 1; $i<=stats::countRooms(); $i++) {
					if($i!=stats::countRooms()){
						echo stats::countLodgeFreqOfRoom($i, $param_month.'-'.$param_year).","; 
					}
				else {
					$month = time_object::pad_zero_before_digit($i);
					echo stats::countLodgeFreqOfRoom($i, $param_month.'-'.$param_year);
				}
				} ?>

						],
						
					}				
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				title: {
					display: true,
					text: 'Lodge Frequency (<?php echo time_object::integer_to_month($param_month) ?>)'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
					callbacks :  {
							label: function (tooltipItem, data) {
								var label = data.datasets[tooltipItem.datasetIndex].label || '';
								if (label) {
									label += ': ';
							}
								label+=tooltipItem.yLabel.toLocaleString();
								return label; 
						}
						}
					
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Room Number',
							fontColor:'#15bd9c'
						},
						ticks: {
						fontColor:'#999999',
						fontFamily:' "Baskerville Old Face"',
						fontSize:'13'
					}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Frequency',
							fontColor:'#ec489c',
						},
						ticks: {
						min: 0,
						stepSize: 1, 
						callback: function(value, index, values) {
							if(parseInt(value)>=1000) {
							return currencySign+value.toLocaleString();
						} else if (parseInt(value)<=-1000) {
							return '- '+currencySign+Math.abs(value).toLocaleString();
						} else {
							return value;
					}
					},
						fontColor:'#4b4b4b',
						fontFamily: '"Avant Garde",Avantgarde,"Century Gothic",CenturyGothic,AppleGothic,sans-serif',
					}
					}]
				}
			}							
		});
		</script>		
		<?php

		###BREAKING IN
	}

}
?>