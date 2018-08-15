<?php
/**
*	@author Clinton Nzedimma, Paul Princewill, Prince Darlington (c) Novacom Webs Nigeria 2018
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Guest Management
* This class contains properties and methods regarding a guest of the hotel.
* This class is highly dependent on 'Guest', 'Room' and 'Sqlite_DB' class
*/



class Guest_Singleton 
{
	private $mainDB;
	private $guest_id;
	private $reservation_id;
	public $data;
	public $room;
	public $today;
	public $date_in;
	public $date_out;
	public $today_obj;
	public $early_date_out;
	public $late_date_out;
	public $DURATION_TO_STAY;
	public $AMOUNT_YIELD_WITH_DAYS;
	public $REFUNDABLE_AMOUNT;
	public $NUMBER_OF_DAYS_LODGED; 

	function __construct ($params)
	{
		$this->mainDB= new sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/main.DB");
		$this->room = new room();
		$this->today=date('Y').'-'.date('m').'-'.date('d') ; // yyyy-mm-dd



		if (is_array($params)) {
			if (count($params) == 1) {
				foreach ($params as $key => $value) {
					if ($key =='guest_id') {
						/**
						* @param if constructor value is guest_id
						*/							
						$this->guest_id = $value;

						$this->data=array (
							// database default row inside array
							"id" =>  $this->get('id'),
							"full_name" => $this->get('full_name'),
							"email" => $this->get('email'),
							"home_address" => $this->get('home_address'),
							"phone" => $this->get('phone'),
							"occupation" => $this->get('occupation'),
							"sex" => $this->get('sex'),
							"amount_paid" => $this->get('amount_paid'),
							"payment_type" => $this->get('payment_type'),
							"checkInDay" => $this->get('checkInDay'),
							"checkInMonth" => $this->get('checkInMonth'),
							"checkInYear" => $this->get('checkInYear'),
							"checkInHour" => $this->get('checkInHour'),
							"checkInMinute" => $this->get('checkInMinute'),
							"checkOutHour" => $this->get('checkOutHour'),
							"checkOutMinute" => $this->get('checkOutMinute'),							
							"checkOutDay" => $this->get('checkOutDay'),
							"checkOutMonth" => $this->get('checkOutMonth'),
							"checkOutYear" => $this->get('checkOutYear'),
							"room_id" => $this->get('room_id'),
							"reservation_id" => $this->get('reservation_id'),
							"earlyCheckOutDay" => $this->get('earlyCheckOutDay'),
							"earlyCheckOutMonth" => $this->get('earlyCheckOutMonth'),
							"earlyCheckOutYear" => $this->get('earlyCheckOutYear'),							
							"lateCheckOutDay" => $this->get('lateCheckOutDay'),
							"lateCheckOutMonth" => $this->get('lateCheckOutMonth'),
							"lateCheckOutYear" => $this->get('lateCheckOutYear'),	


							// peculiar data inside array
							"ROOM_NUMBER" => $this->room->getDataById( $this->get('room_id'), 'number'),
							"ROOM_TYPE" => $this->room->getDataById( $this->get('room_id'), 'type'),
							"ROOM_PRICE" => $this->room->getDataById( $this->get('room_id'), 'price'),
							"CHECK_IN_TIME" => $this->get('checkInHour').":".$this->get('checkInMinute'),
							"CHECK_OUT_TIME" => $this->get('checkOutHour').":".$this->get('checkOutMinute'),
							"DEFAULT_SERVER_CHECK_IN_DATE" => $this->get('checkInYear')."-".$this->get('checkInMonth')."-".$this->get('checkInDay'), // date format yyyy-mm-dd
							"DEFAULT_SERVER_CHECK_OUT_DATE" => $this->get('checkOutYear')."-".$this->get('checkOutMonth')."-".$this->get('checkOutDay'), // date format yyyy-mm-dd
						
							"DEFAULT_SERVER_EARLY_CHECK_OUT_DATE" => $this->get('earlyCheckOutYear')."-".$this->get('earlyCheckOutMonth')."-".$this->get('earlyCheckOutDay'), // date format yyyy-mm-dd
							"DEFAULT_SERVER_LATE_CHECK_OUT_DATE" => $this->get('lateCheckOutYear')."-".$this->get('lateCheckOutMonth')."-".$this->get('lateCheckOutDay'), // date format yyyy-mm-dd


							"CHECK_IN_DATE" => $this->get('checkInDay')."-".time_object::integer_to_month($this->get('checkInMonth'))."-".$this->get('checkInYear'), // date format dd-mm-yyyy	
							"CHECK_OUT_DATE" => $this->get('checkOutDay')."-".time_object::integer_to_month($this->get('checkOutMonth'))."-".$this->get('checkOutYear'),
							"EARLY_CHECK_OUT_DATE" => $this->get('earlyCheckOutDay')."-".time_object::integer_to_month($this->get('earlyCheckOutMonth'))."-".$this->get('earlyCheckOutYear'),
							"LATE_CHECK_OUT_DATE" => $this->get('lateCheckOutDay')."-".time_object::integer_to_month($this->get('lateCheckOutMonth'))."-".$this->get('lateCheckOutYear')							
							);
						/* date_in and date_out are due dates */
						$this->date_in = new DateTime($this->data['DEFAULT_SERVER_CHECK_IN_DATE']);
						$this->date_out = new DateTime($this->data['DEFAULT_SERVER_CHECK_OUT_DATE']);
						$this->today_obj = new DateTime($this->today); //creating object for today

						if (!$this->hasCheckedOut()) {
						/*
						* When the guest has checked out
						*/
						// number of days guest should stay, if date in equals date out, it counts as 1
						$this->DURATION_TO_STAY = ($this->date_in==$this->date_out) ? 1 : $this->date_in->diff($this->date_out)->format('%a'); 

						// number of days guest is in the room, if date out equals date in, it counts as 1
						$this->NUMBER_OF_DAYS_LODGED = ($this->date_out==$this->date_in && $this->date_in==$this->today_obj) ? 1 : $this->date_in->diff($this->today_obj)->format('%a'); 

						// this is the product of the number of days lodged and  price of lodged room
						$this->AMOUNT_YIELD_WITH_DAYS = ($this->date_out==$this->today_obj) ? $this->data["amount_paid"] : $this->NUMBER_OF_DAYS_LODGED * $this->data["ROOM_PRICE"];

						//number of days over stayed,
						$this->DAYS_OVERSTAYED=$this->NUMBER_OF_DAYS_LODGED - $this->DURATION_TO_STAY;			

						// this counts as 0 if date in equals date out else it is the subtraction amount yield with day from amount paid by guest
						$this->REFUNDABLE_AMOUNT = ($this->date_in==$this->date_out  && $this->date_out==$this->today_obj) ? 0 : ($this->hasOverStayed()) ? -($this->DAYS_OVERSTAYED * $this->data["ROOM_PRICE"]) : $this->data["amount_paid"] - $this->AMOUNT_YIELD_WITH_DAYS;
						}

						try {
							/*
							* For early check out
							*/
							$this->early_date_out = new DateTime($this->data["DEFAULT_SERVER_EARLY_CHECK_OUT_DATE"]);
						} catch (Exception $e) {
							// nothing to throw
						}
						try {
							/*
							* For late check out
							*/
							$this->late_date_out = new DateTime($this->data["DEFAULT_SERVER_LATE_CHECK_OUT_DATE"]);	
						} catch (Exception $e) {
							// nothing to throw
						}
			

						if ($this->checkedOutEarly()) {
							/*
							* When the guest checked out early, some properties may change.
							* The properties that change include [NUMBER_OF_DAYS_LODGED], [REFUNDABLE_AMOUNT].
							* Changed properties are relative to the Early Check Out Date
							*/
							$this->DURATION_TO_STAY = ($this->date_in==$this->date_out) ? 1 : $this->date_in->diff($this->date_out)->format('%a');							
							$this->NUMBER_OF_DAYS_LODGED = ($this->early_date_out==$this->date_in && $this->date_in==$this->today_obj) ? 1 : $this->date_in->diff($this->early_date_out)->format('%a');
							$this->AMOUNT_YIELD_WITH_DAYS = ($this->date_out==$this->today_obj) ? $this->data["amount_paid"] : $this->NUMBER_OF_DAYS_LODGED * $this->data["ROOM_PRICE"];		
							$this->REFUNDABLE_AMOUNT = ($this->date_in==$this->early_date_out  && $this->early_date_out==$this->today_obj) ? 0 : $this->data["amount_paid"] - $this->AMOUNT_YIELD_WITH_DAYS;											
						}	


						if ($this->checkedOutLate()) {
							/*
							* When the guest checked out late, some properties may change.
							* The properties that change include [NUMBER_OF_DAYS_LODGED], [REFUNDABLE_AMOUNT].
							* Changed properties are relative to the Late Check Out Date
							*/
							$this->DURATION_TO_STAY = ($this->date_in==$this->date_out) ? 1 : $this->date_in->diff($this->date_out)->format('%a');							
							$this->NUMBER_OF_DAYS_LODGED = ($this->late_date_out==$this->date_in && $this->date_in==$this->today_obj) ? 1 : $this->date_in->diff($this->late_date_out)->format('%a');
							$this->AMOUNT_YIELD_WITH_DAYS = ($this->date_out==$this->today_obj) ? $this->data["amount_paid"] : $this->NUMBER_OF_DAYS_LODGED * $this->data["ROOM_PRICE"];		
							$this->REFUNDABLE_AMOUNT = ($this->date_in==$this->late_date_out  && $this->late_date_out==$this->today_obj) ? 0 : $this->data["amount_paid"] - $this->AMOUNT_YIELD_WITH_DAYS;											


						}		

					} 
					else if ($key =='reservation_id') {
						/**
						* @param if constructor value is reservation_id
						*/						
						$this->reservation_id = $value;

						$this->data=array (
							// database default row inside array
							"id" =>  $this->get('id'),
							"full_name" => $this->get('full_name'),
							"email" => $this->get('email'),
							"home_address" => $this->get('home_address'),
							"phone" => $this->get('phone'),
							"occupation" => $this->get('occupation'),
							"sex" => $this->get('sex'),
							"amount_paid" => $this->get('amount_paid'),
							"payment_type" => $this->get('payment_type'),
							"checkInDay" => $this->get('checkInDay'),
							"checkInMonth" => $this->get('checkInMonth'),
							"checkInYear" => $this->get('checkInYear'),			
							"checkOutDay" => $this->get('checkOutDay'),
							"checkOutMonth" => $this->get('checkOutMonth'),
							"checkOutYear" => $this->get('checkOutYear'),
							"room_id" => $this->get('room_id'),
							"guest_id" => $this->get('guest_id'),
							"cancelled" => $this->get('cancelled'),
							"date_created" => $this->get("date_created"),

							// peculiar data inside array
							"ROOM_NUMBER" => $this->room->getDataById( $this->get('room_id'), 'number'),
							"ROOM_TYPE" => $this->room->getDataById( $this->get('room_id'), 'type'),
							"ROOM_PRICE" => $this->room->getDataById( $this->get('room_id'), 'price'),
							"CHECK_IN_TIME" => $this->get('checkInHour').":".$this->get('checkInMinute'),
							"DEFAULT_SERVER_CHECK_IN_DATE" => $this->get('checkInYear')."-".$this->get('checkInMonth')."-".$this->get('checkInDay'), // date format yyyy-mm-dd
							"DEFAULT_SERVER_CHECK_OUT_DATE" => $this->get('checkOutYear')."-".$this->get('checkOutMonth')."-".$this->get('checkOutDay'), // date format yyyy-mm-dd

							"CHECK_IN_DATE" => $this->get('checkInDay')."-".time_object::integer_to_month($this->get('checkInMonth'))."-".$this->get('checkInYear'), // date format dd-mm-yyyy	
							"CHECK_OUT_DATE" => $this->get('checkOutDay')."-".time_object::integer_to_month($this->get('checkOutMonth'))."-".$this->get('checkOutYear')

							);	

						/* date_in and date_out are due dates */
						$this->date_in = new DateTime($this->data['DEFAULT_SERVER_CHECK_IN_DATE']);
						$this->date_out = new DateTime($this->data['DEFAULT_SERVER_CHECK_OUT_DATE']);
						$this->today_obj = new DateTime($this->today); //creating object for today	
						$this->DURATION_TO_STAY = ($this->date_in==$this->date_out) ? 1 : $this->date_in->diff($this->date_out)->format('%a');	
						$this->DUE_CHECK_IN_DAYS = $this->today_obj->diff($this->date_in)->format('%a');											

					}
				}
			}
		}

	}


	public function get ($par) 
	{
		$par=sanitize_note($par);

		if (isset($this->guest_id)) {
			$db_tbl = 'guests';
			$param_id = $this->guest_id;
		}

		else if (isset($this->reservation_id)) {
			$db_tbl = 'reservations';
			$param_id = $this->reservation_id;
		}


		$sql="SELECT * FROM $db_tbl WHERE id='$param_id' ";
		$query=$this->mainDB->query($sql);
		$num_rows=$this->mainDB->numRows($query);
		if($num_rows!=0) {
			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				switch ($par) {
					case $par:
						$value=$row[$par];
						break;
					default:
						$value="<p style='color:red'> <b>'$par'</b> is a wrong value </p>";
						break;
				}		
			}	
		} else {
			$value=null;
		}
		return $value;		
	}

	 public function hasOverStayed ()
		{
			return ($this->NUMBER_OF_DAYS_LODGED>$this->DURATION_TO_STAY) ? true : false;
		}

	public function hasUnderStayed () 
		{
			return ($this->DURATION_TO_STAY>$this->NUMBER_OF_DAYS_LODGED) ? true : false;
		}

	public function hasCheckedOut ()
		{
			return (!empty($this->data["checkOutHour"]) && !empty($this->data["checkOutMinute"]) ) ? true : false;
		}	



	public function checkedOutEarly()
	{
		return ($this->hasCheckedOut() && !empty($this->data["earlyCheckOutDay"]) && !empty($this->data["earlyCheckOutMonth"]) && !empty($this->data["earlyCheckOutYear"])) ? true : false;
	}

	public function checkedOutLate()
	{
		return ($this->hasCheckedOut() && !empty($this->data["lateCheckOutDay"]) && !empty($this->data["lateCheckOutMonth"]) && !empty($this->data["lateCheckOutYear"])) ? true : false;
	}	

	public function checkedOutOnDueTime () 
	{
		return (!$this->checkedOutEarly() && !$this->checkedOutLate() && $this->hasCheckedOut()) ? true : false;
	}

	private function isCheckingOutEarly () 
	{
		return ($this->date_out>$this->today_obj) ? true :false;
	}

	private function isCheckingOutLate () 
	{
		return ($this->today_obj>$this->date_out) ? true : false ;
	}

	public function wasCheckedInViaReservation () {
		return (!empty($this->data['reservation_id'])) ? true : false ;
	}


	public function checkOut ()
	 {
	 	$current_hour = date('H'); 
	 	 $current_minute = date('i');  
	 	 $current_year = date('Y'); 
	 	 $current_month = date('m');  
	 	 $current_day = date('d');

		if ($this->isCheckingOutEarly()) {
			// checking out early
			$sql="UPDATE guests SET 
			checkOutHour ='$current_hour' ,  
			checkOutMinute = '$current_minute', 
			 earlyCheckOutDay	= '$current_day',
			earlyCheckOutMonth	= '$current_month',
			 earlyCheckOutYear= '$current_year' 
		  	WHERE id='$this->guest_id' ";			
		} else if ($this->isCheckingOutLate()) {
			// checking out late
			$sql="UPDATE guests SET 
			checkOutHour ='$current_hour' ,  
			checkOutMinute = '$current_minute', 
			 lateCheckOutDay	= '$current_day',
			lateCheckOutMonth	= '$current_month',
			 lateCheckOutYear= '$current_year' 
		  	WHERE id='$this->guest_id' ";				
		} else {
			// checking out due
			$sql="UPDATE guests SET checkOutHour ='$current_hour' ,  checkOutMinute = '$current_minute' WHERE id='$this->guest_id' ";
		}
		
		// querying database
		$query=$this->mainDB->query($sql);

		// setting room as vacant
		$this->room->setStatusById($this->data["room_id"] , "VACANT"); 

	}	

	public function undoCheckOut ()
	 {
		$current_hour=date('H');	
		$current_minute=date('i');
		$sql="UPDATE guests SET 
		checkOutHour =NULL , 
		checkOutMinute= NULL,
		earlyCheckOutDay	=NULL,
		earlyCheckOutMonth	=NULL,
		earlyCheckOutYear	=NULL,
		lateCheckOutDay	=NULL,
		lateCheckOutMonth	=NULL,
		lateCheckOutYear=NULL		
		WHERE id='$this->guest_id' ";
		$query=$this->mainDB->query($sql);

		// setting room as lodged
		$this->room->setStatusById($this->data["room_id"] , "LODGED"); 
	}


	public function isInDebt () {
		return ($this->REFUNDABLE_AMOUNT<0) ? true : false;
	}

	public function reservationCheckIn () {
	/**
	*  Checks in guest by inserting reservation data into guests table
	* @method This method is valid if reservation_id in constructor is set.
	*/	

		//values to be submitted to database
		$full_name=sanitize_note($this->data["full_name"]);	
		$email=sanitize_note($this->data["email"]);
		$home_address=sanitize_note($this->data["home_address"]);
		$phone=sanitize_note($this->data["phone"]);
		$occupation=sanitize_note($this->data["occupation"]);
		$sex=sanitize_note($this->data["sex"]);
		$amount_paid=sanitize_note($this->data["amount_paid"]); 
		$checkInDay=sanitize_note($this->data['checkInDay']);
		$checkInMonth=sanitize_note($this->data['checkInMonth']);
		$checkInYear=sanitize_note($this->data['checkInYear']);
		$checkInHour=date('H');	
		$checkInMinute=date('i');
		$checkOutDay=sanitize_note($this->data['checkOutDay']);;
		$checkOutMonth=sanitize_note($this->data['checkOutMonth']);
		$checkOutYear=sanitize_note($this->data['checkOutYear']);		
		$room_id=sanitize_note($this->data["room_id"]);
		$payment_type=sanitize_note($this->data["payment_type"]);



			$sql="INSERT INTO guests (
				id	,
				full_name	,
				email	,
				home_address	,
				phone	,
				occupation	,
				sex	,
				amount_paid, 
				checkInDay	,
				checkInMonth	,
				checkInYear	,
				checkInHour	,
				checkInMinute	,
				checkOutDay	,
				checkOutMonth	,
				checkOutYear	,		
				room_id	,
				payment_type,
				reservation_id
		) 
		VALUES (
				NULL	,
				'$full_name'	,
				'$email'	,
				'$home_address'	,
				'$phone'	,
				'$occupation'	,
				'$sex'	,
				'$amount_paid', 
				'$checkInDay'	,
				'$checkInMonth'	,
				'$checkInYear'	,
				'$checkInHour'	,
				'$checkInMinute'	,
				'$checkOutDay'	,
				'$checkOutMonth'	,
				'$checkOutYear'	,		
				'$room_id'	,
				'$payment_type',
				'$this->reservation_id'			
		)";
		
		$query=$this->mainDB->query($sql);
		$this->room->setStatusById($room_id, "LODGED");		
	}

	public function reservationIsCheckedIn() {
		/**
		* @method checks if reservation has already been checked in
		*/
		return (!empty($this->data['guest_id'])) ? true : false;
	}

	public function reservationIsCancelled () {
		return(!empty($this->data['cancelled'])) ? true : false;
	}

	public function reservationIsUnderDue () {
		return ($this->today_obj < $this->date_in) ? true : false;
	}

	public function reservationIsOverDue () {
		return ($this->today_obj > $this->date_in) ? true : false;
	}

	public function reservationIsDue () {
		return (!$this->reservationIsUnderDue() && !$this->reservationIsOverDue()) ? true : false;
	}			

	public function reservationMapDomain ($param) {
		$param = sanitize_note($param);
		$sql = "UPDATE reservations SET guest_id = '$param' WHERE id ='$this->reservation_id' ";
		$query= $this->mainDB->query($sql);
	}

	public function reservationCancel () {
		$sql = "UPDATE reservations SET cancelled = 1 WHERE id='$this->reservation_id' ";
		$query = $this->mainDB->query($sql);
	}

	public function undoReservationCancel() {
		$sql = "UPDATE reservations SET cancelled = NULL WHERE id = '$this->reservation_id' ";
		$query = $this->mainDB->query($sql);
	}
}

?>