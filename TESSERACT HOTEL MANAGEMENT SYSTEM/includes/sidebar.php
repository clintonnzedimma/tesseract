<div id="main-sidebar">
	<div class="logo-container">
		<img src="css/img/logo-1.png" id="logo">
	</div>

	<ul>
		<li><a href="dashboard.php">Dashboard</a></li>
		<li>
			<a href="general-management.php?lodged&p=1">
				Guests 
			<sup class="red" id="count-guests-to-be-checked-out-today"><?php echo stats::countGuestsToBeCheckedOutToday() ?></sup> 
		<sup class="green" id="count-lodged-guests"></sup> 
		<sup class="yellow" id="count-reservations-to-be-checked-in-today"><?php echo stats::countReservationsToBeCheckedInToday() ?></sup> 
		</a>  
			
		</li>
		<li><a href="guest-registeration.php">Check In</a></li>
		<li><a href="create-reservation.php">Book Down</a></li>
		<li><a href="analytics.php?default">Analytics</a></li>
		<li><a href="about.php">About</a></li>
		<!-- <li><a href="#">Log Book</a></li> -->
		<!-- <li><a href="#">Check In/Check Out</a></li> -->
		<!-- <li><a href="test.php">TEST </a></li> -->
	</ul>

	<div class="non-nav">
		<div class="stat" align="center">

			<span class="name">SUMMARY </span>
		</div>	

		<div class="stat">
			<div class="right-icon">
				<img src="css/icons/gold-chart.png">
			</div>						
			<span class="figure-desc">LODGED GUESTS</span>
			<br>
			<span class="figure"><?php echo stats::countLodgedGuests() ?></span>
		</div>

		<div class="stat">
				<div class="right-icon">
				<img src="css/icons/blue-chart.png">
			</div>			
			<span class="figure-desc">BOOKINGS FOR TODAY</span>
			<br>
			<span class="figure"><?php echo stats::countReservationsToBeCheckedInToday() ?></span>
		</div>		

		<div class="stat">
			<div class="right-icon">
				<img src="css/icons/green-chart.png">
			</div>				
			<span class="figure-desc">NET PROFIT (<?php echo date('F') ?>)</span>
			<br>
			<span class="figure"> <?php echo config::currency('sign'); ?> <?php echo number_format(finance::netRegProfitOf(date('m'), date('Y')) )?> </span>
		</div>
				
	</div>

	<div id="main-sidebar-btn">
		<span></span>	
		<span></span>
		<span></span>
	</div>
</div>

<data PHPtoJS="bobo"></data>

<script>
	$(function(){
		//hiding superscripts
		$("#count-lodged-guests").hide();
		$("#count-guests-to-be-checked-out-today").hide();
		$("#count-reservations-to-be-checked-in-today").hide();

		// when the main sidebar button is clicked
		$("#main-sidebar-btn").click(function() {
		$.getJSON (
			'widgets/json.data.php', 
			'stats', 
			function(data){
				$.each(data.stat, function() {
					if (this['LODGED_GUESTS']>0) {
						$("#count-lodged-guests").html(this['LODGED_GUESTS']);
						$("#count-lodged-guests").show();
					}

					if (this['GUEST_TO_BE_CHECKED_OUT_TODAY']>0) {
						$("#count-guests-to-be-checked-out-today").html(this['GUEST_TO_BE_CHECKED_OUT_TODAY']);
						$("#count-guests-to-be-checked-out-today").show();
					}

					if (this['RESERVATIONS_TO_BE_CHECKED_IN_TODAY']>0) {
						$("#count-reservations-to-be-checked-in-today").html(this['RESERVATIONS_TO_BE_CHECKED_IN_TODAY']);
						$("#count-reservations-to-be-checked-in-today").show();
					}					
				});

		});

	});		

});






</script>


<script src="js/sidebar.toggle.js"></script> 
<!-- Sidebar Toggle Javascript -->
