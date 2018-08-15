<?php


/**
*	@author Clinton Nzedimma (c) Novacom Webs Nigeria 2018
*	@package  Tesseract Hotel Management System v 1.0.0
*	@subpackage Administration
* This class contains admin functions and properties
*/


class admin
{	
	private $config_DB;
	public function __construct ()
	{
		 $this->config_DB=new sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/config.DB");
	}

	public $LOGIN_INPUT_USERNAME; // login page username
	public $LOGIN_INPUT_PASSWORD; // login page password
	public $SESS_USERNAME;



	  public function account_exists($input_username) 
	  {
	  		$input_username=sanitize_note($input_username);
	  		$sql="SELECT username FROM managers WHERE username='$input_username' ";
	  		$query=$this->config_DB->query($sql);
	  		$numrows=$this->config_DB->numRows($query);

	  		if ($numrows!=0) {
	  			return true;
	  		}

	}



	  public function check_login_details($input_username, $input_password) 
	  {
	  		$input_username=sanitize_note($input_username);
	  		$input_password=sanitize_note(grease($input_password)); 

	  		$sql="SELECT * FROM managers WHERE username='$input_username' ";
	  		$query=$this->config_DB->query($sql);
	  		$numrows=$this->config_DB->numRows($query);

	  		if ($numrows!=0) {
	  			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
	  				if ($input_username==$row["username"] && $input_password==$row["password"]) {
	  					// the login details are correct
	  					return true;

	  				}
	  			}	
	  		}

		 }

 	 public function session_auth()
 		{
 			$this->LOGIN_INPUT_USERNAME=sanitize_note($this->LOGIN_INPUT_USERNAME);
 			$this->LOGIN_INPUT_PASSWORD=sanitize_note($this->LOGIN_INPUT_PASSWORD);

 			
	 			$_SESSION["admin_auth_username"]=$this->LOGIN_INPUT_USERNAME;
	 			$_SESSION["admin_auth_password"]=$this->LOGIN_INPUT_PASSWORD;
 		}	

 	public function isLoggedIn ()
 		{
 		 	if	(isset($_SESSION["admin_auth_username"]) && isset ($_SESSION["admin_auth_password"])) {
 		 			if ($this->check_login_details($_SESSION["admin_auth_username"], $_SESSION["admin_auth_password"])) {
 		 				return true;
 		 			}
 		 			
 				}
 		}	


 	public function protect_page () 
 		{
 			if (!$this->isLoggedIn()) {
 				header("Location:index.php?not_logged");
 			}
 		}	

 	public function protect_ModalWindow ()
 		 {
 			if (!$this->isLoggedIn()) {
 				echo " <script> window.close(); </script>";
 			}
 		}	


 	public function get($par) 
 	{
 		if ($this->isLoggedIn()) {
 			$SESS_USERNAME=$_SESSION["admin_auth_username"];
 			$sql="SELECT * FROM managers WHERE username='$SESS_USERNAME'";
 			$query=$this->config_DB->query($sql);
 			$numrows=$this->config_DB->numRows($query);

 			if ($numrows!=0) {
 				while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
 					switch ($par) {
 						case 'username':
 							$value=$row['username'];
 							break;

						case 'password':
 							$value=$row['password'];
 							break; 

 						default:
 							$value="<p style='color:red'> <b>'$par'</b> is a wrong value </p>";
 							break;
 					}
 				}
 				return $value;
 			}
		}
 	}	


	 //end of class
}



?>