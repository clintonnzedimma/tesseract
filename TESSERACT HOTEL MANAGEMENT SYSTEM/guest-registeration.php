<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<?php 
include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";
include "engine/functions/database/class.mainDB.php";
include "engine/functions/core/init.php";
include "engine/functions/core/errors.php";
include "engine/functions/core/class.time_object.php";
include "engine/functions/core/class.admin.php";
include "engine/functions/core/class.config.php";
include "engine/functions/core/class.room.php";
include "engine/functions/core/class.stats.php";
include "engine/functions/core/class.guest.php";
include "engine/functions/core/class.guest_singleton.php";

$admin= new admin();
$admin->protect_page();

$room=new room();

$config= new config();

$guest= new guest();


$WINDOW_NAME="Guest Registeration";




?>

<html>
<head>
<?php include('includes/meta-main.php'); ?>	
	<title><?php echo($WINDOW_NAME); ?></title>
</head>
<body>
<?php include('includes/sidebar.php'); //sidebar ?>
<?php include('includes/topbar.php'); //topbar?>


<?php
$today=date('Y').'-'.date('m').'-'.date('d') ;
$today_by_next_year= 1+date('Y').'-'.date('m').'-'.date('d') ;
$icon_error="<img src='css/icons/red-error.png' id='error-icon'>";

if (empty($_POST)==false) { 
$required_fields=array ('full_name', 'phone', 'sex', 'amount_paid', 'checkIn_date', 'checkOut_date'); //fields that must be have input values
foreach ($_POST as $key => $value) {
	if (empty($value) && in_array($key, $required_fields) ===true) {
		$errors[]="$icon_error Fill all mandatory * fields ";
		break 1;
	}
	}
} //REQUIRED FIELDS END HERE




if (submit_btn_clicked('guest-registeration-button')) {
	$full_name=sanitize_note($_POST["full_name"]);
	$email=sanitize_note($_POST["email"]);
	$home_address=sanitize_note($_POST["home_address"]);
	$phone=sanitize_note($_POST["phone"]);
	$occupation=sanitize_note($_POST["occupation"]);
	$sex=sanitize_note($_POST["sex"]);
	$checkIn_date=sanitize_note($_POST["checkIn_date"]);
	$checkOut_date=sanitize_note($_POST["checkOut_date"]);
	$room_id=sanitize_note($_POST["room_id"]);
	$payment_type=sanitize_note($_POST["payment_type"]);
	$amount_paid=sanitize_note($_POST["amount_paid"]);

	//confining option fields
	$confine_sex=array('NULL','male', 'female');
	$confine_payment_type=array('CASH PAYMENT', 'BANK TRANSFER', 'DEBIT CARD');

	//dates
	$date1=new DateTime("$checkIn_date");
	$date2=new DateTime("$checkOut_date");
	$number_of_days=$date1->diff($date2)->format('%a'); // difference between date

	$get_room_price=$room->getDataById($room_id, 'price');

	if ($number_of_days==0) {
		$amount_for_number_of_days=$get_room_price; 
	} else {
		$amount_for_number_of_days=$get_room_price*$number_of_days;
	}





	if (empty($full_name)) {
		$errors[]="$icon_error Full Name cannot be empty";
	}

	if (!sanitize_integer($phone)) {
		$errors[]="$icon_error Input a valid phone number";
	}

	if (!input_confine($sex, $confine_sex)) {
		$errors[]="$icon_error Invalid Input for sex' ";
	}
	if ($sex=='NULL') {
		$errors[]="$icon_error Please select the sex of the Guest";
	}

	if (!input_confine($payment_type,$confine_payment_type)) {
		$errors[]="$icon_error Invalid Input";
	}
	if (!sanitize_email($email) && !empty($email)) {
		$errors[]=" $icon_error Please enter a valid mail";
	}

	if($checkIn_date!=$today) {
		$errors[]="$icon_error Invalid Date";
	}


	if (!sanitize_integer($amount_paid)) {
		$errors[]="$icon_error Input a valid amount";
	}	

	if (empty($checkOut_date)) {
		$errors[]="$icon_error Please Input check out date";
	}

	if ($date1>$date2) {
		//if checkIn date is greater than check out date
		$errors[]="$icon_error Invalid date: Check Out Date must be after Check In Date";
	}

	if ($room->isLodgedById($room_id)) {
		$errors[]="$icon_error Room already taken";
	}

	if ($amount_for_number_of_days>$amount_paid) {
		if ($number_of_days==0 || $number_of_days==1 ) {
			$errors[]="$icon_error The amount to be paid 1 day for ROOM".$room->getDataById($room_id,'number');
		} else if ($number_of_days>1) {
			$errors[]="$icon_error The amount to be paid for ".$number_of_days." days for ROOM ".$room->getDataById($room_id,'number');
		}
	}

	if ($room->isLodgedById($room_id)) {
		$errors[]="$icon_error The room selected is lodged";
	}

}


?>



<?php 
if (!isset($_GET["success"])) {
	include 'includes/specifics/guest_registeration_form.php';
} else {
	// success
	include 'includes/specifics/guest_registeration_success.php';
}
 ?>




<script src="js/custom/live-data.1.0.0.js"></script>
<!-- Getting Live Data from 'live.data.php' -->
<script src="/js/custom/main-lib.1.0.0.js"></script>
<!--Central Javascript Library -->

<data PHPtoJS="minimumDateText"><?php echo $today;  //today ?></data>
<data PHPtoJS="maximumDateText"><?php echo $today_by_next_year; //today's date by next year?></data>

<data PHPtoJs="success_status"><?php if (isset($_GET["success"])) echo true; ?></data>
<data PHPtoJS="checkIn_date_range-STATUS"></data> <!-- Holds either 0 or 1 and works with  dateInRangeStatus() && dateInRangeCheckViaJSON() Local  JS functions -->
<data PHPtoJS="checkOut_date_range-STATUS"></data><!--Holds either 0 or 1 and works with  dateOutRangeStatus() && dateOutRangeCheckViaJSON() Local JS functions   -->
<data PHPtoJS="roomType"></data>
<script type="text/javascript">
	regulateFormInputDate('#checkIn_date', '#checkOut_date'); // regulating form input data for date
</script>

<!-- IN PAGE JAVASCRIPT -->
<script type="text/javascript">
/*
* @author Clinton Nzedimma 
*/
	$(function(){
		var errorFullNameStatus=false;
		var errorPhoneStatus=false;
		var errorEmailStatus=false;
		var errorRoomStatus=false;
		var errorSexStatus=false;

		var icon_error="<img src='css/icons/red-error.png' id='error-icon' class='icon'>";	

		 $('input[name=full_name]').focusout(function (){
		 	checkFullName(this);
		 });

		 $('input[name=home_address]').focusout(function (){
			var varInputAttrName=$(this).attr("name");
			window[varInputAttrName]=$(this).val();		 
		  });
		 

		$('input[name=phone]').focusout(function (){ 
			checkPhoneNumber(this);
		});

		 $('input[name=email]').focusout(function (){
		 	checkEmail(this);


		  });

		$('input[name=occupation]').focusout(function (){ 
			var varInputAttrName=$(this).attr("name");
			window[varInputAttrName]=$(this).val();
		});


		 $('input[name=amount_paid]').focusout(function (){ 
			var varInputAttrName=$(this).attr("name");
			window[varInputAttrName]=$(this).val();
		 });
		 



		$('select[name=room_id]').on('change',function (){ 
			checkRoomField(this);

			var numOfDays=daysBetween($('input[name=checkIn_date]').change().val(), $('input[name=checkOut_date]').val() ); // number of days

			if ($(this).val()==null || $(this).val()=="#" ) {
				// if room id is null or equals #
				 $('input[name=checkOut_date]').attr("disabled", "disabled"); 
				 $('input[name=checkOut_date]').attr("class", "disabled-date");
				 $('input[name=checkOut_date]').val(false);
				 $('input[name=amount_paid]').val(false);
				 
				 if ($('input[name=checkIn_date]').change().val()==$('input[name=checkOut_date]').change().val()) {
				 	// if check in date and check out date are the same
				 	$('#amount_paid-DESCRIPTION').html("");
				 	$('#amount_paid-DESCRIPTION').show();
				 }
			} else {
				$('input[name=checkOut_date]').attr("disabled", false);
				 $('input[name=checkOut_date]').attr("class", false); 
			}

			//getting json data
			$.getJSON(
				'widgets/json.data.php',
				'room_id='+$('select[name=room_id]').val(), 
			function (data) {
				$.each(data.room, function() {
					var liveRoomTypeAndPrice="";
					$('[PHPtoJS=roomType]').append(this['type']);
					$('div#item span#type').append(this['type']);
					$('div#item span#price').append(this['price']);

						if ($('input[name=checkOut_date]').change().val().length==0) {
							// if checkout date hold no value, the amount paid should be room amount

							$('input[name=amount_paid]').val(parseInt(this['price']) *1); 
							console.log((parseInt(this['price']) *1));
						}else {
							//the amount paid should be the product of room price and number of days between check in & checkout
							$('input[name=amount_paid]').val(parseInt(this['price'])* parseInt(numOfDays));
							console.log(parseInt(this['price'])* parseInt(numOfDays));
					}
			});

			}
		);				

		});





		$('input[name=checkOut_date]').on('change', function () {
					dateOutRangeCheckViaJSON();
					var numOfDays=daysBetween($('input[name=checkIn_date]').change().val(), $(this).change().val() );

					//getting json data
					$.getJSON(
						'widgets/json.data.php',
						'room_id='+$('select[name=room_id]').val(), 
					function (data) {
						$('#item').empty();
						$.each(data.room, function() {
								if ($('input[name=checkOut_date]').change().val()==$("[PHPtoJS=minimumDateText]").text() && $("select[name=room_id]").change().val().length!=0) {
									// if check out date equals [minimumDateText]and room id holds no value, amount paid should be room price
									$('input[name=amount_paid]').val(parseInt(this['price']));
								}else {
									$('input[name=amount_paid]').val(parseInt(this['price'])* parseInt(numOfDays));
							}
					});

					}
				);
				});		



		
		 $('input[name=payment_type]').focusout(function (){
			var varInputAttrName=$(this).attr("name");
			window[varInputAttrName]=$(this).val();
		 });



		 $('input[name=checkIn_date]').focusout(function (){ 
			var varInputAttrName=$(this).attr("name");
			window[varInputAttrName]=$(this).val();
		 });




		 $('input[name=checkOut_date]').focusout(function (){ 
			var varInputAttrName=$(this).attr("name");
			window[varInputAttrName]=$(this).val();

			dateCheck(this);
		 });



		 $('input[name=sex]').on('change', function (){
			var varInputAttrName=$(this).attr("name");
			window[varInputAttrName]=$(this).val();
			if (sex=="male" || sex=="female") {
				$("#NULL-sex").attr("checked", false);
				$("#sex-ERROR").hide();		
				if (sex=="male")
					{
						$("input[value=male]").attr("checked", true);
						$("input[value=female]").attr("checked", false);
					}	else if (sex=="female") {
						$("input[value=female]").attr("checked", true);
						$("input[value=male]").attr("checked", false);
					}else {

					}	
				errorSexStatus=false;		
			}

		 });




/*
*Local functions peculiar to this page 
*/

function checkFullName(selector) {
			var full_name=$(selector).val();

			if (full_name.length==0 || full_name.trim().length==0) {
				$('#full_name-ERROR').html(icon_error+"Please input full name");
				$('#full_name-ERROR').show();
				errorFullNameStatus=true;
				return true;
			} else {
				$('#full_name-ERROR').hide();
				return false;
			}
}


function checkPhoneNumber(selector) {
			var phone=$(selector).val();
			if (phone.length==0) {
				errorPhoneStatus=true;	
				$('#phone-ERROR').html(icon_error+"Please input your phone number");
				$('#phone-ERROR').show();
				return true;
			} else if (phone.length<11) {
				errorPhoneStatus=true;
				$('#phone-ERROR').html(icon_error+"Please input 11 digits");
				$('#phone-ERROR').show();
				return true;
			} 
			else {
				$('#phone-ERROR').hide();
				return false;
			}	
}


function checkRoomField(selector) {
			var room_id= $(selector).val();
			if (room_id=="#") {
				errorRoomStatus=true;
				$('#room_id-ERROR').html(icon_error+'Choose Room');
				$('#room_id-ERROR').show();
				return true;
			} else {
				$('#room_id-ERROR').hide();
				return false;
			}	
}

function checkEmail(selector) {
	var email=$(selector).val();

			if (!validateEmail(email) && email.length>0) {
				errorEmailStatus=true;
				$('#email-ERROR').html(icon_error+"Please input a valid email");
				$('#email-ERROR').show();	
				return true;			

			}else if (email.length==0) {
				$('#email-ERROR').hide();
				return false;	
			}

			else {
				$('#email-ERROR').hide();
				return false;	
			} 	
}


function checkSex() {
	var sex=$('input[name=sex]:checked').val();
	if (sex=="NULL") {
		errorSexStatus=true;
		$("#sex-ERROR").html(icon_error+"Please choose sex");
		$("#sex-ERROR").show();
		return true;
	} else {
		$("#sex-ERROR").hide();
		return false;
	}

	
}

function dateCheck (selector) {
	var date=$(selector).val();
	if (date.length==0) {
		$("#checkOut_date-ERROR").html(icon_error+"Please Input Check Out Date");
		$("#checkOut_date-ERROR").show();
		return true;
	} else {
		$("#checkOut_date-ERROR").hide();
		return false;
	}
}

function dateInRangeCheckViaJSON() {
	var date = $('input[name=checkIn_date]').val();
	if (date.length>0) {
		var getData = {
			check_room_id :	$("select[name=room_id]").val(),
			check_date: date
		};

		//getting json data
		$.getJSON(
			"widgets/json.data.php", getData, 
		function (data) {
			//console.log(data.status);

			if (data.status==true) {
				$("#checkIn_date_range-ERROR").html(icon_error+"This Date is Unavailable");
				$("#checkIn_date_range-ERROR").show();
				$("[PHPtoJs=checkIn_date_range-STATUS]").html(1); //submitting value to identfied [PHPtoJS] data holder by changing inner HTML		
			} else {
				$("#checkIn_date_range-ERROR").hide();
				$("[PHPtoJs=checkIn_date_range-STATUS]").html(0);
			}

		}
	);
}	

}


function dateOutRangeCheckViaJSON() {
	var date = $('input[name=checkOut_date]').val();
	if (date.length>0) {
		var getData = {
			check_room_id :	$("select[name=room_id]").val(),
			check_date: date
		};

		//getting json data
		$.getJSON(
			"widgets/json.data.php", getData, 
		function (data) {
			console.log(data.status);

			if (data.status==true) {
				$("#checkOut_date_range-ERROR").html(icon_error+"This Date is Unavailable");
				$("#checkOut_date_range-ERROR").show();	
				$("[PHPtoJs=checkOut_date_range-STATUS]").html(1);  //submitting value to identified [PHPtoJS] data holder by changing inner HTML
			} else {
				$("#checkOut_date_range-ERROR").hide();
				$("[PHPtoJs=checkOut_date_range-STATUS]").html(0);
				return false;
			}


		}
	);
}	

}

/*
* dateInRangeStatus() &&  dateOutRangeStatus() work with their respective identified [PHPtoJS] data holder
* They return TRUE if their respective date form holds value and [PHPtoJS] data holder equals 1
* They also return FALSE if their respective date form holds value and [PHPtoJS] data holder equals 0
*/

function dateInRangeStatus() {
	if ($("[PHPtoJs=checkIn_date_range-STATUS]").html()==1 && $('input[name=checkIn_date]').val().length>0) {
		return true;
	} else if ($("[PHPtoJs=checkIn_date_range-STATUS]").html()==0 && $('input[name=checkIn_date]').val().length>0) {
		return false;
	}
}

function dateOutRangeStatus() {
	if ($("[PHPtoJs=checkOut_date_range-STATUS]").html()==1 && $('input[name=checkOut_date]').val().length>0) {
		return true;
	} else if ($("[PHPtoJs=checkOut_date_range-STATUS]").html()==0 && $('input[name=checkOut_date]').val().length>0) {
		return false;
	}
}





		 $("#GUEST-REGISTERATION").submit(function (e) {
		 	checkSex();
			checkFullName("input[name=full_name]");
			checkPhoneNumber("input[name=phone]");
			checkRoomField("select[name=room_id] option:selected");
			checkEmail("input[name=email]");
			dateInRangeCheckViaJSON();
			dateOutRangeCheckViaJSON();
			dateCheck('input[name=checkOut_date]');						
	
		 	if (checkFullName("input[name=full_name]")  || checkPhoneNumber("input[name=phone]") || checkSex() || checkEmail("input[name=email]") || checkRoomField("select[name=room_id] option:selected")  || dateCheck('input[name=checkOut_date]') || dateInRangeStatus() || dateOutRangeStatus() ) {
		 		e.preventDefault(); // do not submit form
		 	}


		 });



		

});




</script>

<div id="item">


</div>



</body>
</html>