<?php


/**
*	@author Clinton Nzedimma (c) Novacom Webs Nigeria 2018
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Configuration
* 	@static This class contains properties and methods regarding the configuration of the application
*/
class Config extends Admin
{
		
	public static $DB;	
	 public function  __construct()
		{
			self::$DB=new sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/config.DB");
		}

	
	 public static function currency($par)
		{
			$sql="SELECT * FROM currency WHERE use_status='1'";
			$query=self::$DB->query($sql);
			$numrows=self::$DB->numRows($query);
			if ($numrows!=0) {
				while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
					switch ($par) {
						case 'id':
							$value=$row['id'];
						break;

						case 'name':
							$value=$row['name'];
						break;

						case 'sign':
							$value=$row['currency_sign'];
							break;

						case 'abbrev':
							$value=$row['abbrev'];
							break;	

						case 'country':
							$value=$row['country'];
							break;


						default:
							$value="<p style='color:red'> <b>'$par'</b> is a wrong value </p>";
							break;
					}
				}
			return $value;
			}
		}


	public static function info ($par) {
			$sql="SELECT * FROM info WHERE id='1' ";
			$query=self::$DB->query($sql);
			$numrows=self::$DB->numRows($query);
			if ($numrows!=0) {
				while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
					switch ($par) {
						case $par:
							$value = $row[$par];
						break;

						default:
							$value="";
						break;

					}
				}
				return $value;
	}

}

	public static function test() {
		var_dump(self::$DB);
	}	


	public static function updateSecurityDetails ($new_username, $new_password) {
		$new_username = sanitize_note($new_username);
		$new_password = sanitize_note(grease($new_password));
		$sql = "UPDATE managers SET username = '$new_username', password = '$new_password'";
		$query = self::$DB->query($sql); 
	}

	public static function changeCurrency($new_id) {
		$old_id = sanitize_note(self::currency('id'));
		$new_id = sanitize_note($new_id);
		$old_sql = "UPDATE currency SET use_status =  NULL WHERE id = '$old_id'"; //setting old currency use status as null
		self::$DB->query($old_sql);
		$new_sql = "UPDATE currency SET use_status =  '1' WHERE id = '$new_id'"; //setting new currency use status as 1
		self::$DB->query($new_sql);
	}		


	public static function listCurrencyToSet () {
		$sql = "SELECT * FROM currency";
		$query =self::$DB->query($sql);
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			###BREAKING OUT
			?>	
			<option type="radio" value="<?php echo $row['id'] ?>" <?php selectPostConstCMP(self::currency('id'), $row['id']) ?>> <?php echo $row['name'] ?> <span>(<?php echo $row['currency_sign'] ?>)</span> </option>
			<?php
		}
	}

	public static function updateHotelName($param) {
		$param = sanitize_note($param);
		if (strlen($param)) {
			$sql= "UPDATE info SET hotel_name = '$param' WHERE id='1'";
			$query=self::$DB->query($sql);			
		}
	}

}
new config(); // static declaration 

?>