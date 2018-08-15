<?php
ob_start();
session_start();
/**
*	@author Clinton Nzedimma (c) Novacom Webs Nigeria 2018
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Data Management
*	This file gets data from database as JSON
* 	This module is highly dependent on the included PHP classes
*/
include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";
include "../engine/functions/database/class.mainDB.php";
include "../engine/functions/core/init.php";
include "../engine/functions/core/errors.php";
include "../engine/functions/core/class.time_object.php";
include "../engine/functions/core/class.admin.php";
include "../engine/functions/core/class.config.php";
include "../engine/functions/core/class.room.php";
include "../engine/functions/core/class.guest.php";
include "../engine/functions/core/class.guest_singleton.php";
include "../engine/functions/core/class.stats.php";
$admin= new admin();

$config= new config();

$room=new room();

$guest= new guest();


$mainDB=new sqlite_DB("../engine/databases/main.DB");

/**
* @param $_GET['room_id'] is for the primary key of room in database
* When isset, it returns a single room data if it exists in database
*/
if (isset($_GET['room_id'])) {
	$room_id = sanitize_note($_GET['room_id']);
	
	$sql = "SELECT * FROM rooms WHERE id='$room_id'";
	$query = $mainDB->query($sql);
	$num_rows = $mainDB->numRows($query);
	
	$room=array();
	if ($num_rows!=0) {
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			array_push($room, $row);
		}
		echo json_encode(array('room' => $room));

	}


}


/**
* @param $_GET['check_room_id'] is  for the primary key of room in database
* @param $_GET['check_date'] is the input date to be checked
* When both are set, it returns a boolean value
* It is TRUE if a guests has check date is in a particular date range of Lodged Guests or Reservations
*/

if (isset($_GET['check_room_id']) && isset($_GET['check_date'])) {
	$check_room_id = sanitize_note($_GET['check_room_id']);
	$check_date = sanitize_note($_GET['check_date']);

	if ($guest->generalGuestsDateRangeCheck($check_room_id, $check_date) || $guest->generalReservationsDateRangeCheck($check_room_id, $check_date)) {
		$retval = array("status" => true);
		echo json_encode($retval);
	} else {
		$retval = array("status" => false);
		echo json_encode($retval);		
	}
}

/**
* @param $_GET['stats']
* When isset, it returns statistics in form
*/
if (isset($_GET['stats'])) {
	$data  = array(array(
		'GUEST_TO_BE_CHECKED_OUT_TODAY' =>  stats::countGuestsToBeCheckedOutToday(),
		'LODGED_GUESTS' => stats::countLodgedGuests(),
		'RESERVATIONS_TO_BE_CHECKED_IN_TODAY' => stats::countReservationsToBeCheckedInToday(),
		'OVERSTAYED_GUESTS' => stats::countOverstayedGuests(),
		'OVERSTAYED_GUESTS_NOT_CHECKED_OUT' => stats::countOverstayedGuestsNotCheckedOut()
		 ));

	echo json_encode(array('stat' => $data))	;
}


/**
* @param $_GET['check_password']
* When isset, it returns whether password is the same or not
*/
if (isset($_GET['check_password'])) {
	$check_password = sanitize_note(grease($_GET['check_password'])); 
	if ($check_password == $admin->get('password')) {
		$retval = array("status" => true);
		echo json_encode($retval);
	} else {
		$retval = array("status" => false);
		echo json_encode($retval);
	}


}

?>