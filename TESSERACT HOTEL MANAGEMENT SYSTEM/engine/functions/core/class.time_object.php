<?php

class time_object {
	/* 
		This class contains any method or function related to time
	*/

	public static function integer_to_month ($integer) {

		switch ($integer) 
			{	
				case 1:
					$value='January';
					break;

				case 2:
					$value='February';
					break;

				case 3:
					$value='March';
					break;

				case 4:
					$value='April';
					break;			

				case 5:
					$value='May';
					break;

				case 6:
					$value='June';
					break;

				case 7:
					$value='July';
					break;

				case 8:
					$value='August';
					break;

				case 9:
					$value='September';
					break;

				case 10:
					$value='October';
					break;	

				case 11:
					$value='November';
					break;

				case 12:
					$value='December';
					break;

				default:
					$value="<b style='color:red'>Invalid Integer. Acceptable values for month are 1-12 </b>";																		
			}	

			return $value;
	}

	public static function month_to_integer ($param) {

		switch ($param) 
			{	
				case 'January':
				case 'Jan':
				case 'jan':
					$value=1;
					break;

				case 'February':
				case 'Feb':
				case 'feb':
					$value=2;
					break;

				case 'March':
				case 'Mar':
				case 'mar':
					$value=3;
					break;

				case 'April':
				case 'Apr':
				case 'apr':
					$value=4;
					break;			

				case 'May':
				case 'may':
					$value=5;
					break;

				case 'June':
				case 'Jun':
				case 'jun':
					$value=6;
					break;

				case 'July':
				case 'Jul':
				case 'jul':
					$value=7;
					break;

				case 'August':
				case 'Aug':
				case 'aug':
					$value=8;
					break;

				case 'September':
				case 'Sep':
				case 'sep':
					$value=9;
					break;

				case 'October':
				case 'Oct':
				case 'oct':
					$value=10;
					break;	

				case 'November':
				case 'Nov':
				case 'nov':
					$value=11;
					break;

				case 'December':
				case 'Dec':
				case 'dec':
					$value=12;
					break;

				default:
					$value="<b style='color:red'>Invalid paramter <i>$param</i> </b>";																		
			}	

			return self::pad_zero_before_digit($value);
	}



	public static function pad_zero_before_digit($num) {
		// this function pads digits less than 10 to have 0 before it i.e 1==01, 5==05 etc.
		if ($num>9) {
			return $num;
		}
		
		else {
			return '0'. $num;
		}	
	} 


	public static function check_in_range ($input_date, $start_date, $end_date) {
		$input_date_obj = new DateTime ($input_date);
		$start_date_obj = new DateTime ($start_date);
		$end_date_obj = new DateTime ($end_date);

		return (($input_date_obj>=$start_date_obj) && ($input_date_obj<=$end_date_obj)) ? true : false;

	}

	public static function manual_date_format ($param, $separator) {
		$date = explode('-', $param);
		return $date[2]."$separator".self::integer_to_month($date[1])."$separator".$date[0];
	}



}

?>