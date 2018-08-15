<?php 

if (submit_btn_clicked('update-globals')) {
	$currency = $_POST['currency_id'];
	$hotel_name = $_POST['hotel_name'];

	if ($currency==null || $currency=="#") {
		$errors[]="Please select a currency";
	}
}

?>
<center>
	<div class="settings-global">
<?php
			if (empty($errors)==true)
				{
					if(submit_btn_clicked('update-globals')) {
						config::changeCurrency($_POST['currency_id']);
						config::updateHotelName($_POST['hotel_name']);
						$success[] = "<b style='color:#15bd9c'>GLOBAL SETTINGS SAVED SUCESSFULLY </b>";
						$SUCCESS_MESSAGE = error_msg($success);
						echo($SUCCESS_MESSAGE);
					}			
				}		

			else {
				$ERROR_MESSAGES = error_msg($errors);
				echo "<div align='center' style='color:red'>".$ERROR_MESSAGES." </div>";
			}

?>		
		<form id="settings-global-form" method="POST" action="<? $_PHP_SELF?>">
			<b>Currency: </b>	<?php echo config::currency('name') ?> (<?php echo config::currency('sign') ?>) <br>
			<select name="currency_id">
				<option value="#">-Select-</option>
			<?php config::listCurrencyToSet(); ?>
			</select>
			<br>
			<br>
			<b>Hotel Name:</b> <br>
			<input type="text" name="hotel_name" placeholder="<?php echo config::info('hotel_name') ?>">
			<br>
			<span style="color:#727272; font-size: 11px;">Please leave hotel name if you do not want to change it</span>
			<br>
			<br>
			<input type="submit" name="update-globals" value="Update Globals">
			
		</form>
	</div>
</center>




<script>
	$(function() {
		

		$("select[name=currency_id]").on('change', function() {

		});




		function checkCurrency(selector) {
			param = $(selector).val();
			return (param=="#") ? true : false; 
		}

		$("#settings-global-form").submit(function(e){
			
			if (checkCurrency("select[name=currency_id] option:selected")) {
				//e.preventDefault();
			}


		});

	});
</script>