<center>
	<div class="guest-registeration" id="form">
		<div class="inner">

		<?php
			if (empty($errors)==true)
				{	
					if (submit_btn_clicked('guest-registeration-button')) {
						echo"<h2 style='color:green'>Guest Registered </h2>";		
						$guest->register();	// register guest
						header("Location:guest-registeration.php?success");
						exit();
					}
				} else {
				$ERROR_MESSAGES= error_msg($errors);
				echo "<div class='error-msg'> $ERROR_MESSAGES </div>";
				
			}			
		?>
			 
		<form id="GUEST-REGISTERATION" action="guest-registeration.php" method="POST">
			<h1>CONTACT DETAILS</h1>
			<div class="inner">
				<div class="left-side">
					<span class="field">Full Name: <b id="mandatory-star">*</b> </span> <input type="text" name="full_name" value="<?php postConst('full_name'); ?>">
					<div class="js-field-error" id="full_name-ERROR">  </div>
					<br>
					<span class="field">Home Address:  </span> <input type="text" name="home_address" value="<?php postConst('home_address'); ?>">
					<div class="js-field-error" id="home_address-ERROR">  </div>
					<br>
					<span class="field">Phone: <b id="mandatory-star">*</b></span> <input type="number" class="phone" name="phone" pattern="[0-9]{6,}" title="Please Input a number" value="<?php postConst('phone'); ?>">
					<div class="js-field-error" id="phone-ERROR">  </div>
				</div>	

				<div class="right-side">
					<span class="field">Email:</span> <input type="text" name="email" value="<?php postConst('email'); ?>">
						<div class="js-field-error" id="email-ERROR">  </div>
					<br>
					<span class="field">Occupation:</span> <input type="text" name="occupation" value="<?php postConst('occupation'); ?>">
					<div class="js-field-error" id="occupation-ERROR">  </div>
					<br>
					<input type="radio" name="sex" value="NULL"  style="display: none;" <?php checkPostConst('sex', 'NULL'); ?> id="NULL-sex"  checked>
					 <input type="radio" name="sex" value="male" <?php checkPostConst('sex', 'male'); ?> > Male <input type="radio" name="sex" value="female" <?php checkPostConst('sex', 'female'); ?> > Female
					<br>
					 <div class="js-field-error" id="sex-ERROR">  </div>
				</div>
			</div>	
			<h1>ROOM DETAILS</h1>
			<div class="inner">
				<div class="left-side">
						<span class="field">Amount Paid <b id="mandatory-star">*</b> </span>
						<span id="currency-sign"><?php echo $config->currency('sign'); ?> </span>
						<input type="number" name="amount_paid" value="<?php postConst('amount_paid'); ?>" class="disabled-field" readonly/>
						<div class="js-field-error" id="amount_paid-ERROR">  </div>
						<div class="js-field-error" id="amount_paid-DESCRIPTION">  </div>
						<br>
					<span class="field">Room Number <b id="mandatory-star">*</b></span> 
						<select name="room_id" id="vacant_room_search_GUEST_REGISTERATION" >
							<option value="#"> --Choose--</option>
							<?php $room->optionsOfVacantRooms('room_id');?>
						</select>
						<div class="js-field-error" id="room_id-ERROR">  </div>
						<div id="liveRoomType_AND_Price"></div>	<!-- Live GET Data via Jquery for room type and price  -->
						<span class="field">Payment Type <b id="mandatory-star">*</b></span>
						<select name="payment_type">
							<option value="CASH PAYMENT" <?php selectPostConst('payment_type', 'CASH PAYMENT'); ?> > Cash Payment</option>
							<option value="BANK TRANSFER" <?php selectPostConst('payment_type', 'BANK TRANSFER'); ?> >Bank Transfer</option>
							<option value="DEBIT CARD" <?php selectPostConst('payment_type', 'DEBIT CARD'); ?> > Debit Card (MasterCard, Verve, VISA etc.)</option>
						</select>
						<br>	
						<div class="js-field-error" id="payment_type-ERROR">  </div>				
				</div>

				<div class="right-side">
					<span class="field"> Check In Date </span> <input type="date" name="checkIn_date" id="checkIn_date" value="<?php echo $today;  ?>" min="<?php echo  $today;  ?>" max="<?php echo $today; ?>"  /> <!-- Check In Date Input -->
					<div class="js-field-error" id="checkIn_date-ERROR">  </div>
					<div class="js-field-error" id="checkIn_date_range-ERROR">  </div>	
					<br>				
					<span class="field">Check Out Date </span> <input type="date" name="checkOut_date" id="checkOut_date" min="<?php echo $today; ?>" max="<?php echo $today_by_next_year; ?>" value="<?php postConst('checkOut_date'); ?>" disabled="disabled" class="disabled-date"/>	  <!-- Check Out Date Input -->	
					<div class="js-field-error" id="checkOut_date-ERROR">  </div>
					<div class="js-field-error" id="checkOut_date_range-ERROR">  </div>		
				</div>
			</div>

			<div>
				<input type="submit" name="guest-registeration-button" value="Register Guest" >
			</div>
		</form>		
			
		</div>

	</div>


</center>