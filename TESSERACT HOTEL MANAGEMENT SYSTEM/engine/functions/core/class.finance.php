<?php 
/**
*	@author Clinton Nzedimma, Bobby Nzedimma Novacom Webs Nigeria 2018
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Statistics, Analytics
* 	@static This class method is for the hotel financial accounting of Guest Registeration & Reservation
*/


class Finance
{
	public static $mainDB;
	function __construct()
	{
		self::$mainDB = new sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/main.DB");	
	}

	public static function grossRegProfitOf($param_month, $param_year) {
		/**
		* @param Gross Registeration Profit of param_month and param_year	
		*/
		$param_month = sanitize_note($param_month);
		$param_year =  sanitize_note($param_year);

		$sql = " SELECT * FROM guests WHERE checkInMonth = '$param_month' AND checkInYear = '$param_year' ";
		$query = self::$mainDB->query($sql);
		$sum = 0;
		$the_guest = array();
		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["guest_id" => $row['id']]); 
			$sum = $sum + $the_guest[$row['id']]->data['amount_paid']; 
		}
		return $sum;		
	}

	public static function grossRegProfitOfYear ($param_year) {
		/**
		* @param Gross Registeration Profit of param_year
		*/		
		$param_year = sanitize_note($param_year);
		$sum = 0;
		for ($i = 1; $i<=12; $i++) {
			$sum = $sum + self::grossRegProfitOf(time_object::pad_zero_before_digit($i), $param_year);
		}
		return $sum;
	}

	public static function disbursementRegOf($param_month, $param_year) {
		/**
		* @param  Money the spends in context of Guest Registeration. It is contextual expense of param_month and param_year 
		* The Absolute value of Guest REFUDNABLE_AMOUNT is used for computation here
		*/
		$param_month = sanitize_note($param_month);
		$param_year =  sanitize_note($param_year);

		$sql = " SELECT * FROM guests WHERE checkInMonth = '$param_month' AND checkInYear = '$param_year' ";
		$query = self::$mainDB->query($sql);
		$sum = 0;
		$the_guest = array();
		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["guest_id" => $row['id']]); 
			$sum = $sum + abs($the_guest[$row['id']]->REFUNDABLE_AMOUNT);
		}
		return $sum;		
	}


	public static function netRegProfitOf($param_month, $param_year) {
		/**
		* @param Net Registeration Profit of param_month and param_year
		*/
		$param_month = sanitize_note($param_month);
		$param_year =  sanitize_note($param_year);
		return self::grossRegProfitOf($param_month, $param_year) - self::disbursementRegOf($param_month, $param_year);		
	}

	public static function netRegProfitOfYear ($param_year) {
		/**
		* @param Net Registeration Profit of param_year
		*/
		$param_year = sanitize_note($param_year);
		$sum = 0;
		for ($i = 1; $i<=12; $i++) {
			var_dump(self::netRegProfitOf(time_object::pad_zero_before_digit($i), $param_year));
			$sum = $sum + self::netRegProfitOf(time_object::pad_zero_before_digit($i), $param_year);
		}
		return $sum;
	}	


	public static function totalBookDownDeposits($param_month, $param_year) {
		/**
		* @param Gross Book Down Profit of param_month and param_year	
		*/
		$param_month = sanitize_note($param_month);
		$param_year =  sanitize_note($param_year);

		$sql = " SELECT * FROM reservations WHERE checkInMonth = '$param_month' AND checkInYear = '$param_year' ";
		$query = self::$mainDB->query($sql);
		$sum = 0;
		$the_guest = array();
		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["reservation_id" => $row['id']]); 
			$sum = $sum + $the_guest[$row['id']]->data['amount_paid']; 
		}
		return $sum;		
	}

	public static function disbursementBookDownOf($param_month, $param_year) {
		/**
		* @param Gross Book Down Profit of param_month and param_year	
		* It sums of amount paid of Cancelled Reservations
		*/
		$param_month = sanitize_note($param_month);
		$param_year =  sanitize_note($param_year);

		$sql = " SELECT * FROM reservations WHERE checkInMonth = '$param_month' AND checkInYear = '$param_year' ";
		$query = self::$mainDB->query($sql);
		$sum = 0;
		$the_guest = array();
		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["reservation_id" => $row['id']]);
			if ($the_guest[$row['id']]->reservationIsCancelled())  {
				$sum = $sum + $the_guest[$row['id']]->data['amount_paid']; 
			}
		}
		return $sum;		
	}	

	public static function netBookDownProfitOf($param_month, $param_year) {
		/**
		* @param Net Registeration Profit of param_month and param_year
		*/
		$param_month = sanitize_note($param_month);
		$param_year =  sanitize_note($param_year);
		return self::totalBookDownDeposits($param_month, $param_year) - self::disbursementBookDownOf($param_month, $param_year);		
	}

	public static function grossGrowthRateMonths($param1, $param2) {
		$param1 = explode('-', $param1);	
		$param2 = explode('-', $param2);
		return 100 * (self::grossRegProfitOf($param2[0],$param2[1]) - self::grossRegProfitOf($param1[0],$param1[1])) / self::grossRegProfitOf($param2[0],$param2[1]); 
	}

	public static function netGrowthRateMonths($param1, $param2) {
		$param1 = explode('-', $param1);	
		$param2 = explode('-', $param2);
		return 100 * ((self::netRegProfitOf($param2[0],$param2[1]) - self::netRegProfitOf($param1[0],$param1[1])) / self::netRegProfitOf($param1[0],$param1[1])); 
	}	

}
 new finance(); // static declaration for constructor to work
?>