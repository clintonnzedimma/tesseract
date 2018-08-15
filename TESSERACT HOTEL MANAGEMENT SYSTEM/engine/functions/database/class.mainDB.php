<?php

/*
 * Main Database Connection	
 * Type: SQLite3
 * Database File Extension: .db
 * Author: Clinton Nzedimma
 	This function connects to the primary database file in the directory	
 */



class mainDB extends SQLite3 {
	function __construct ($directory) {
		$this->open($directory.'/main.db'); // directory of database file
	}

	public function numRows ($query) {
		//this function returns the number for any query in sqlite
		$numrows=NULL;
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			//looping through query
	  		$numrows++;
			}
	  		return $numrows;
	}	
}

?>