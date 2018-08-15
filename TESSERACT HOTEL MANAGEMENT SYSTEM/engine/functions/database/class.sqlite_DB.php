<?php

/**
*	@author Clinton Nzedimma
*	@package  Tesseract Hotel Management System
*	@subpackage Database
*/

class sqlite_DB extends SQLite3 {
	public function __construct ($file_name) {
		$this->open($file_name); // open directory file
	}

	public function numRows ($query) {
		//this function returns the number for any query in sqlite
		$numrows=NULL;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
	  			$numrows++; //looping through query
			}
	  		return $numrows;
	}


}




?>
