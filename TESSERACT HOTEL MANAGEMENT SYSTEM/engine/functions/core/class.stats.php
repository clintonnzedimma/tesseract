<?php
 
/**
*	@author Clinton Nzedimma Novacom Webs Nigeria 2018
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Statistics
* 	@static This class method is for statistics
*/

require_once ($_SERVER['DOCUMENT_ROOT']."/engine/functions/core/class.finance.php");

 class Stats extends Finance
 {
 	public static $mainDB;
 	public function __construct()
 	{
 		self::$mainDB = new sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/main.DB");
 	}
 	public static function test() {
		var_dump(self::$mainDB);
	}	

 	public static function countGuestsToBeCheckedOutToday()
 	{
		$today=date('Y').'-'.date('m').'-'.date('d') ;
		$today_split= explode('-', $today);
		$sql = "SELECT * FROM guests WHERE checkOutYear = '$today_split[0]' AND checkOutMonth = '$today_split[1]' AND checkOutDay = '$today_split[2]' AND checkOutHour IS NULL AND checkOutMinute IS NULL";
		$query=self::$mainDB->query($sql);
		$num_rows=self::$mainDB->numRows($query);
	
		$count_check = 0;
		while($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["guest_id" =>$row['id']] );
			if (!$the_guest[$row['id']]->hasCheckedOut() && $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_OUT_DATE"]==$today) {
				$count_check++;
			} 	

		}
			 return $count_check;

	}

	public static function countLodgedGuests() {
		$sql = "SELECT * FROM guests";
		$query=self::$mainDB->query($sql);
		$num_rows=self::$mainDB->numRows($query);

		$count_check = 0;

		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$the_guest[$row['id']] = new Guest_Singleton(["guest_id" =>$row['id']] );	
				if (!$the_guest[$row['id']]->hasCheckedOut()) {
					$count_check++;
				} 
			}	
			return $count_check;	
	}

 	public static function countReservationsToBeCheckedInToday()
 	{
		$today=date('Y').'-'.date('m').'-'.date('d') ;
		$today_split= explode('-', $today);
		$sql = "SELECT * FROM reservations WHERE checkInYear = '$today_split[0]' AND checkInMonth = '$today_split[1]' AND checkInDay = '$today_split[2]'";
		$query=self::$mainDB->query($sql);
		$num_rows=self::$mainDB->numRows($query);
	
		$count_check = 0;
		while($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["reservation_id" =>$row['id']] );
			if (!$the_guest[$row['id']]->reservationIsCancelled () && !$the_guest[$row['id']]->reservationIsCheckedIn ()  && $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_IN_DATE"]==$today) {
				$count_check++;
			} 	

		}
			 return $count_check;

	}

	public static function countRegOnDate($param_year, $param_month, $param_day) {
		/**
		* @method count registerations on given parameter year and month and day
		*/
		$param_year = sanitize_note($param_year);
		$param_month = sanitize_note($param_month);
		$param_day = sanitize_note($param_day);
		$sql = "SELECT * FROM guests WHERE checkInDay = '$param_day' AND checkInMonth = '$param_month' AND checkInYear ='$param_year'  ";
		$query = self::$mainDB->query($sql);
		$count_check = 0;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$count_check++;
		}
		return $count_check;
	} 


	public static function countBookDownOnDate($param_year, $param_month, $param_day) {
		/**
		* @method count reservations on given parameter year and month and day
		*/			
		$param_year = sanitize_note($param_year);
		$param_month = sanitize_note($param_month);
		$param_day = sanitize_note($param_day);
		$sql = "SELECT * FROM reservations ";
		$query = self::$mainDB->query($sql);
		$count_check = 0;
		$conc_date = $param_year."-".$param_month."-".$param_day;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["reservation_id" =>$row['id']] );
				if ($conc_date==$the_guest[$row['id']]->data['date_created']) {
					$count_check++;
				}
		}
		return $count_check;
	} 


	public static function countRegThisYear ($param_year) {
		/**
		* @method counts guests registered on given parameter year
		*/
		$param_year = sanitize_note($param_year);
		$sql = "SELECT * FROM guests WHERE checkInYear ='$param_year'  ";
		$query = self::$mainDB->query($sql);
		$count_check = 0;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$count_check++;
		}
		return $count_check;
	} 

	public static function countBookDownThisYear ($param_year) {
		/**
		* @method count reservations on given parameter year
		*/
		$param_year = sanitize_note($param_year);
		$sql = "SELECT * FROM reservations WHERE checkInYear ='$param_year'  ";
		$query = self::$mainDB->query($sql);
		$count_check = 0;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$count_check++;
		}
		return $count_check;
	} 


	public static function countRegThisMonth ($param_month, $param_year) {
		/**
		* @method count registerations on given parameter year and month
		*/		
		$param_month = sanitize_note($param_month);
		$param_year = sanitize_note($param_year);
		$sql = "SELECT * FROM guests WHERE checkInMonth = '$param_month' AND checkInYear ='$param_year'  ";
		$query = self::$mainDB->query($sql);
		$count_check = 0;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$count_check++;
		}
		return $count_check;
	} 

	public static function countBookDownThisMonth ($param_month, $param_year)
	 {
		/**
		* @method count reservations on given parameter year and month
		*/			
		$param_month = sanitize_note($param_month);
		$param_year = sanitize_note($param_year);
		$sql = "SELECT * FROM reservations WHERE checkInMonth = '$param_month' AND checkInYear ='$param_year'  ";
		$query = self::$mainDB->query($sql);
		$count_check = 0;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$count_check++;
		}
		return $count_check;
	}

	public static function countOverStayedGuests()
	 {	
	 	/**
	 	* @method counts over stayed guests
	 	*/
	 	$sql = "SELECT * FROM guests";
	 	$query = self::$mainDB->query($sql);
	 	$count_check = 0;
	 	$the_guest =  array();
	 	while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
	 		$the_guest[$row['id']] = new Guest_Singleton(['guest_id' => $row['id']]);
	 		if ($the_guest[$row['id']]->hasOverstayed()) {
	 			$count_check++;
	 		}
	 	}
	 	return $count_check;
	}

	public static function countOverStayedGuestsNotCheckedOut()
	 {	
	 	/**
	 	* @method counts over stayed guests
	 	*/
	 	$sql = "SELECT * FROM guests";
	 	$query = self::$mainDB->query($sql);
	 	$count_check = 0;
	 	$the_guest =  array();
	 	while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
	 		$the_guest[$row['id']] = new Guest_Singleton(['guest_id' => $row['id']]);
	 		if ($the_guest[$row['id']]->hasOverstayed() && !$the_guest[$row['id']]->hasCheckedOut()) {
	 			$count_check++;
	 		}
	 	}
	 	return $count_check;

	}	

	public static function countLodgeFreqOfRoom($param_id, $param_period) {
		/**
		* @method counts number of times a room has been lodged in month
		*/
		$param_id = sanitize_note($param_id);
		$period = explode('-', sanitize_note($param_period));
		$sql = "SELECT * FROM guests WHERE room_id = '$param_id' AND checkInMonth = '$period[0]' AND checkInYear = '$period[1]' ";
		$query = self::$mainDB->query($sql);
		$count_check = 0;

		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$count_check++;
		}
		return $count_check;
	}

	public static function countLodgeFreqOfRoomInYear($param_id,$param_year) {
		/**
		* @method counts number of times a room has been lodged in year
		*/
		$sum = 0;
		for ($i=1; $i <=12 ; $i++) { 
			$sum = $sum + self::countLodgeFreqOfRoom($param_id, time_object::pad_zero_before_digit($i).'-'.$param_year);
		}
		return $sum;
	}	

	public static function countRooms() {
		$sql = "SELECT * FROM rooms";
		$query = self::$mainDB->query($sql);
		return self::$mainDB->numRows($query);
	}	

}
new stats(); // static declaration for constructor to work
?>