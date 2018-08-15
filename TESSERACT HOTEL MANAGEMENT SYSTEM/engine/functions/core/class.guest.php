<?php

/**
*	@author Clinton Nzedimma (c) Novacom Webs Nigeria 2018
*	@package  tesseract Hotel Management System v 1.0.0
*	@subpackage Guest Management
* This class contains properties and methods regarding the guests of the hotel
* This class is highly dependent on 'Guest_Singleton', 'Room' and 'Sqlite_DB' class
* This class extends @abstract class 'Paginaton'
*/

require_once ($_SERVER['DOCUMENT_ROOT']."/engine/functions/core/class.abstract.pagination.php");

class Guest extends Pagination
{
	private $mainDB;
	private $room;
	private $config;


	function __construct () {
		$this->mainDB= new sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/main.DB");
		$this->room=new room();
		$this->config= new config();
	}


	public function createGuestDBTable () {
		$sql="CREATE TABLE guests (
		id	INTEGER PRIMARY KEY AUTOINCREMENT,
		full_name	TEXT,
		email	TEXT,
		home_address	TEXT,
		phone	TEXT,
		occupation	TEXT,
		sex	TEXT,
		amount_paid INTEGER,
		checkInDay	TEXT,
		checkInMonth	TEXT,
		checkInYear	TEXT,
		checkInHour	TEXT,
		checkInMinute	TEXT,
		checkOutDay	TEXT,
		checkOutMonth	TEXT,
		checkOutYear	TEXT,
		checkOutHour	TEXT,
		checkOutMinute	TEXT,		
		room_id	INTEGER,
		payment_type TEXT,
		earlyCheckOutDay	TEXT,
		earlyCheckOutMonth	TEXT,
		earlyCheckOutYear	TEXT,
		lateCheckOutDay	TEXT,
		lateCheckOutMonth	TEXT,
		lateCheckOutYear	TEXT
	)";
	$query=$this->mainDB->query($sql);

	}


	public function createReservationDBTable () {
		$sql="CREATE TABLE reservations (
		id	INTEGER PRIMARY KEY AUTOINCREMENT,
		full_name	TEXT,
		email	TEXT,
		home_address	TEXT,
		phone	TEXT,
		occupation	TEXT,
		sex	TEXT,
		amount_paid INTEGER,
		checkInDay	TEXT,
		checkInMonth	TEXT,
		checkInYear	TEXT,
		checkInHour	TEXT,
		checkInMinute	TEXT,
		checkOutDay	TEXT,
		checkOutMonth	TEXT,
		checkOutYear	TEXT,
		checkOutHour	TEXT,
		checkOutMinute	TEXT,		
		room_id	INTEGER,
		payment_type TEXT,
		guest_id INTEGER
	)";
	$query=$this->mainDB->query($sql);

	}
	

	public function register() {
		/**
		* This method registers a a guest directly
		*/
		//exploding dates yyyy/mm/dd 
		$date_in=explode("-", sanitize_note($_POST["checkIn_date"])); 
		$date_out=explode('-', sanitize_note($_POST["checkOut_date"]));

		//values to be submitted to database
		$full_name=sanitize_note($_POST["full_name"]);	
		$email=sanitize_note($_POST["email"]);
		$home_address=sanitize_note($_POST["home_address"]);
		$phone=sanitize_note($_POST["phone"]);
		$occupation=sanitize_note($_POST["occupation"]);
		$sex=sanitize_note($_POST["sex"]);
		$amount_paid=sanitize_note($_POST["amount_paid"]); 
		$checkInDay=$date_in[2];
		$checkInMonth=$date_in[1];
		$checkInYear=$date_in[0];
		$checkInHour=date('H');	
		$checkInMinute=date('i');
		$checkOutDay=$date_out[2];
		$checkOutMonth=$date_out[1];
		$checkOutYear=$date_out[0]	;		
		$room_id=sanitize_note($_POST["room_id"]);
		$payment_type=sanitize_note($_POST["payment_type"]);

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
				payment_type
		) 
		VALUES (
				NULL,
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
				'$checkInMinute',
				'$checkOutDay'	,
				'$checkOutMonth',
				'$checkOutYear',		
				'$room_id',
				'$payment_type'				
		)";
		
		$query=$this->mainDB->query($sql);
		$this->room->setStatusById($room_id, "LODGED");
	}


	public function bookDownRoom() {
		/**
		* This method creates reservation for the guest
		*/
		//exploding dates yyyy/mm/dd 
		$date_in=explode("-", sanitize_note($_POST["checkIn_date"])); 
		$date_out=explode('-', sanitize_note($_POST["checkOut_date"]));
		$today=date('Y').'-'.date('m').'-'.date('d') ;
		//values to be submitted to database
		$full_name=sanitize_note($_POST["full_name"]);	
		$email=sanitize_note($_POST["email"]);
		$home_address=sanitize_note($_POST["home_address"]);
		$phone=sanitize_note($_POST["phone"]);
		$occupation=sanitize_note($_POST["occupation"]);
		$sex=sanitize_note($_POST["sex"]);
		$amount_paid=sanitize_note($_POST["amount_paid"]); 
		$checkInDay=$date_in[2];
		$checkInMonth=$date_in[1];
		$checkInYear=$date_in[0];
		$checkInHour=date('H');	
		$checkInMinute=date('i');
		$checkOutDay=$date_out[2];
		$checkOutMonth=$date_out[1];
		$checkOutYear=$date_out[0]	;		
		$room_id=sanitize_note($_POST["room_id"]);
		$payment_type=sanitize_note($_POST["payment_type"]);
		

			$sql="INSERT INTO reservations (
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
				checkOutDay	,
				checkOutMonth	,
				checkOutYear	,		
				room_id	,
				payment_type,
				date_created
		) 
		VALUES (
				NULL,
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
				'$checkOutDay'	,
				'$checkOutMonth',
				'$checkOutYear',		
				'$room_id',
				'$payment_type',
				'$today'			
		)";
		
		$query=$this->mainDB->query($sql);
	}	



public function recentList($param) {
	/** 
	* @param is the limit of number of data that shoud be displayed
	*/
	$sql="SELECT * FROM guests ORDER BY id DESC LIMIT $param";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	if ($num_rows!=0) {
		while($row=$query->fetchArray(SQLITE3_ASSOC)) {
			###BREAKING OUT
			?>	
				<span class="guest">
					<img src="css/icons/grey-user.png" class="user-icon">
					<a href="javascript:window.open('includes/modal/manage-guest.php?guest_id=<?php echo $row['id']; ?>', '', 'width=800,height=700')">		
						<p id="Name"> 
						<?php echo $row["full_name"]; ?>
						</p>

						<p id="room-Number">
							ROOM: <?php echo $this->room->numberById($row["room_id"]); ?>
						</p>

						<p id="phone-Number">
							<?php echo $row["phone"]; ?> 
						</p>
					</a>			
				</span>		
			<?php
			#BREAKING IN
		}
	}	
}

public function recentListToBeCheckedOutToday($param) {
	/** 
	* @param is the limit of number of data that shoud be displayed
	*/
	$today=date('Y').'-'.date('m').'-'.date('d') ;
	$today_split= explode('-', $today);
	$sql="SELECT * FROM guests WHERE checkOutYear = '$today_split[0]' AND checkOutMonth = '$today_split[1]' AND checkOutDay = '$today_split[2]' AND checkOutHour IS NULL AND checkOutMinute IS NULL ORDER BY id DESC LIMIT $param";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	$count_check = null;
	if ($num_rows!=0) {
		while($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["guest_id" =>$row['id']] );
			if (!$the_guest[$row['id']]->hasCheckedOut() && $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_OUT_DATE"]==$today) :
			###BREAKING OUT
			?>	
				<span class="guest">
					<img src="css/icons/grey-user.png" class="user-icon">
					<a href="javascript:window.open('includes/modal/manage-guest.php?guest_id=<?php echo $row['id']; ?>', '', 'width=800,height=700')">		
						<p id="Name"> 
						<?php echo $the_guest[$row['id']]->data["full_name"]; ?>
						</p>

						<p id="room-Number">
							ROOM: <?php echo $the_guest[$row['id']]->data["ROOM_NUMBER"]; ?>
						</p>

						<p id="phone-Number">
							<?php echo $the_guest[$row['id']]->data["phone"]; ?>
						</p>
					</a>			
				</span>		
			<?php
			#BREAKING IN
			endif;
			
		}
	}

	else {
		###BREAKING OUT
		?>
				<span class="guest">
					<h1 class="null-guests">No guests is due for check out today</h1>
					</a>			
				</span>	
		<?php
	}	
}



public function reservationsToBeCheckedInToday($param) {
	/** 
	* @param is the limit of number of data that shoud be displayed
	*/
	$today=date('Y').'-'.date('m').'-'.date('d') ;
	$today_split= explode('-', $today);
	$sql="SELECT * FROM reservations WHERE checkInYear = '$today_split[0]' AND checkInMonth = '$today_split[1]' AND checkInDay = '$today_split[2]' ORDER BY id DESC LIMIT $param";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	$count_check = null;
	if ($num_rows!=0) {
		while($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["reservation_id" =>$row['id']] );
			if (!$the_guest[$row['id']]->reservationIsCancelled () && !$the_guest[$row['id']]->reservationIsCheckedIn ()  && $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_IN_DATE"]==$today) :
			###BREAKING OUT
			?>	
				<span class="guest">
					<img src="css/icons/grey-user.png" class="user-icon">
					<a href="javascript:window.open('includes/modal/manage-guest.php?reservation_id=<?php echo $row['id']; ?>', '', 'width=800,height=700')">		
						<p id="Name"> 
						<?php echo $the_guest[$row['id']]->data["full_name"]; ?>
						</p>

						<p id="room-Number">
							ROOM: <?php echo $the_guest[$row['id']]->data["ROOM_NUMBER"]; ?>
						</p>

						<p id="phone-Number">
							<?php echo $the_guest[$row['id']]->data["phone"]; ?>
						</p>
					</a>			
				</span>		
			<?php
			#BREAKING IN
			endif;
			
		}
	}

	else {
		###BREAKING OUT
		?>
				<span class="guest">
					<h1 class="null-guests">No Reservation to process today</h1>
					</a>			
				</span>	
		<?php
	}	
}


////
public function overStayedGuestsToBeCheckedOut($param) {
	/** 
	* @param is the limit of number of data that shoud be displayed
	*/
	$today=date('Y').'-'.date('m').'-'.date('d') ;
	$today_split= explode('-', $today);
	$sql="SELECT * FROM guests";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	$count_check = null;
	if ($num_rows!=0) {
		while($row=$query->fetchArray(SQLITE3_ASSOC)) {
			$the_guest[$row['id']] = new Guest_Singleton(["guest_id" =>$row['id']] );
			if ($the_guest[$row['id']]->hasOverstayed() && !$the_guest[$row['id']]->hasCheckedOut()) :
			###BREAKING OUT
			?>	
				<span class="guest">
					<img src="css/icons/grey-user.png" class="user-icon">
					<a href="javascript:window.open('includes/modal/manage-guest.php?guest_id=<?php echo $row['id']; ?>', '', 'width=800,height=700')">		
						<p id="Name"> 
						<?php echo $the_guest[$row['id']]->data["full_name"]; ?>
						</p>

						<p id="room-Number">
							ROOM: <?php echo $the_guest[$row['id']]->data["ROOM_NUMBER"]; ?>
						</p>

						<p id="phone-Number">
							<?php echo $the_guest[$row['id']]->data["phone"]; ?>
						</p>
					</a>			
				</span>		
			<?php
			#BREAKING IN
			endif;
			
		}
	}

	else {
		###BREAKING OUT
		?>
				<span class="guest">
					<h1 class="null-guests">No Reservation to process today</h1>
					</a>			
				</span>	
		<?php
	}	
}



	public function getById($input_id, $par) {
		$input_id=sanitize_note($input_id);
		$par=sanitize_note($par);

		$sql="SELECT * FROM guests WHERE id='$input_id' ";
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


	public function testMethod () {
		//custom count
		$sql = "SELECT * FROM guests";
		$query = $this->mainDB->query($sql);
		$num_rows =  $this->mainDB->numRows($query);

		$count = NULL; 
		if ($num_rows!=0) {
			while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
				if ($row['amount_paid']>=50000) {
					$count++;
				}
			}
			return $count;
		}
	}

	public function generalGuestsDateRangeCheck($input_room_id, $input_date)
	{
		$input_room_id = sanitize_note($input_room_id);
		$sql="SELECT * FROM guests WHERE room_id='$input_room_id' ";
		$query= $this->mainDB->query($sql);
		$num_rows =  $this->mainDB->numRows($query);

		$the_guest = array();
		$count_check = NULL;
		if ($num_rows!=0) {
			while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
					$the_guest[$row['id']] = new guest_singleton(['guest_id' => $row['id']]);
					
					if (!$the_guest[$row['id']]->hasCheckedOut() && time_object::check_in_range($input_date, $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_IN_DATE"],  $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_OUT_DATE"] ))  {
						$count_check ++;
					}
			}

			return ($count_check>0) ? true :false;
		}

	}

	public function generalReservationsDateRangeCheck($input_room_id, $input_date)
	{
		$input_room_id = sanitize_note($input_room_id);
		$sql="SELECT * FROM reservations WHERE room_id='$input_room_id' ";
		$query= $this->mainDB->query($sql);
		$num_rows =  $this->mainDB->numRows($query);

		$the_guest = array();
		$count_check = NULL;
		if ($num_rows!=0) {
			while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
					$the_guest[$row['id']] = new guest_singleton(['reservation_id' => $row['id']]);
					
					if (!$the_guest[$row['id']]->reservationIsCancelled() && time_object::check_in_range($input_date, $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_IN_DATE"],  $the_guest[$row['id']]->data["DEFAULT_SERVER_CHECK_OUT_DATE"] ))  {
						$count_check ++;
					}
			}

			return ($count_check>0) ? true :false;
		}

	}	

	public function displayLodgedGuestsTable($param) {
	/**
	* @param is the number of result per page
	* This method displays guest that are lodged
	* @var $the_guest[] is an array that creates multiple instances of 'Guest_Singleton'
	*/		
		$this->get_num_result_per_page = $param;
		$search_starting_limit_number=($this->get_page_num-1) * $this->get_num_result_per_page; 

		$sql= "SELECT * FROM guests WHERE checkOutHour ='' AND checkOutMinute ='' OR checkOutHour IS NULL AND checkOutMinute IS NULL  ORDER BY id DESC LIMIT $search_starting_limit_number, $this->get_num_result_per_page";
		$query= $this->mainDB->query($sql);
		$num_rows = $this->mainDB->numRows($query);

		$the_guest= array();
		$serial_number= NULL;

		if($num_rows!=0) {
			echo '
		<table cellspacing="0" cellpadding="8" width="100%">
			<tr class="fields">
				<th> S\N</th>
				<th>Full Name</th>
				<th>Room Number</th>
				<th>Room Type</th>
				<th>Checked In On</th>
				<th>To Check Out</th>
				<th>Amount Deposited</th>
				<th>Refundable Amount</th>
				<th>Phone</th>
				<th>Sex</th>
				<th>Payment Type</th>
			</tr>
			';
			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$the_guest[$row['id']] = new guest_singleton (['guest_id' => $row['id']]);

				if (true) {
						$serial_number++;
						$animation_duration = ($serial_number*400>3000) ? 3000 : $serial_number*400;
						### BREAKING OUT
						?>
			<tr align="center" class="records"  data-aos="fade-in" data-aos-duration="<?php echo $animation_duration ?>">
				<td> <?php echo $serial_number ?> </td>
				<td><a href="javascript:window.open('includes/modal/manage-guest.php?guest_id=<?php echo $row['id']; ?>&mdl_src=general-management', '', 'width=800,height=700')"><?php echo $the_guest[$row['id']]->data['full_name']  ?> </a></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_NUMBER']  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_TYPE']  ?></td>
				<td><?php echo date('g:i a', strtotime($the_guest[$row['id']]->data["CHECK_IN_TIME"])) ?>, <?php echo $the_guest[$row['id']]->data["CHECK_IN_DATE"] ?></td>
				<td><?php echo $the_guest[$row['id']]->data['CHECK_OUT_DATE']  ?></td>
				<td> <span id="currency-sign"><?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->data['amount_paid'])  ?></td>
				<td><span id="currency-sign"> <?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->REFUNDABLE_AMOUNT)  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['phone'])  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['sex'][0]  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['payment_type'] ) ?></td>
			</tr>									

						<?php 
						### BREAKING IN
				}
		

			}
		echo '</table>'	;

		$paginate_sql = "SELECT * FROM guests WHERE checkOutHour ='' AND checkOutMinute ='' OR checkOutHour IS NULL AND checkOutMinute IS NULL";
		$paginate_query = $this->mainDB->query($paginate_sql);
		$paginate_numrows = $this->mainDB->numRows($paginate_query);
		$number_of_pages=ceil($paginate_numrows/$this->get_num_result_per_page); 	
		$this->display_pagination_links($number_of_pages);		
		} 
	}

	public function displayCheckedOutGuestsTable($param) {
	/**
	* @param is the number of result per page
	* This method displays guest that have checked out
	* @var $the_guest[] is an array that creates multiple instances of 'Guest_Singleton'
	*/		
		$this->get_num_result_per_page = $param;
		$search_starting_limit_number=($this->get_page_num-1) * $this->get_num_result_per_page; 	

		$sql= "SELECT * FROM guests ORDER BY id DESC LIMIT $search_starting_limit_number, $this->get_num_result_per_page";
		$query= $this->mainDB->query($sql);
		$num_rows = $this->mainDB->numRows($query);

		$the_guest= array();
		$serial_number= NULL;

		if($num_rows!=0) {
			echo '
		<table cellspacing="0" cellpadding="8" width="100%">
			<tr class="fields">
				<th> S\N</th>
				<th>Full Name</th>
				<th>Room Number</th>
				<th>Room Type</th>
				<th>Checked In On</th>
				<th>Checked Out</th>
				<th>Amount Deposited</th>
				<th>Refundable Amount</th>
				<th>Phone</th>
				<th>Sex</th>
				<th>Payment Type</th>
			</tr>
			';
			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$the_guest[$row['id']] = new guest_singleton (['guest_id' => $row['id']]);
				$animation_duration = ($serial_number*400>3000) ? 3000 : $serial_number*400;

				if ($the_guest[$row['id']]->hasCheckedOut()) {
						$serial_number++;

						### BREAKING OUT
						?>
			<tr align="center" class="records" data-aos="fade-in" data-aos-duration="<?php echo $animation_duration ?>">
				<td> <?php echo $serial_number ?> </td>
				<td><a href="javascript:window.open('includes/modal/manage-guest.php?guest_id=<?php echo $row['id']; ?>&mdl_src=general-management', '', 'width=800,height=700')"><?php echo $the_guest[$row['id']]->data['full_name']  ?> </a></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_NUMBER']  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_TYPE']  ?></td>
				<td><?php echo date('g:i a', strtotime($the_guest[$row['id']]->data["CHECK_IN_TIME"])) ?>, <?php echo $the_guest[$row['id']]->data["CHECK_IN_DATE"] ?></td>
				<td><?php if($the_guest[$row['id']]->checkedOutOnDueTime()) {echo $the_guest[$row['id']]->data['CHECK_OUT_DATE']; } if ($the_guest[$row['id']]->checkedOutEarly()) {echo $the_guest[$row['id']]->data['EARLY_CHECK_OUT_DATE']; }  if ($the_guest[$row['id']]->checkedOutlate()) {echo $the_guest[$row['id']]->data['LATE_CHECK_OUT_DATE']; }   ?></td>
				<td> <span id="currency-sign"><?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->data['amount_paid'])  ?></td>
				<td><span id="currency-sign"> <?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->REFUNDABLE_AMOUNT)  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['phone'])  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['sex'][0]  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['payment_type'] ) ?></td>
			</tr>									

						<?php 
						### BREAKING IN
				}
		

			}
		echo '</table>'	;
		$paginate_sql = "SELECT * FROM guests WHERE checkOutHour >0 AND checkOutMinute >0 ";
		$paginate_query = $this->mainDB->query($paginate_sql);
		$paginate_numrows = $this->mainDB->numRows($paginate_query);
		$number_of_pages=ceil($paginate_numrows/$this->get_num_result_per_page); 	
		$this->display_pagination_links($number_of_pages);				
		} 
	}	

	


	public function displayReservationsTable($param) {
	/**
	* @param is sql query limit
	* This method displays guest that have checked out
	* @var $the_guest[] is an array that creates multiple instances of 'Guest_Singleton'
	*/		

		$this->get_num_result_per_page = $param;
		$search_starting_limit_number=($this->get_page_num-1) * $this->get_num_result_per_page;

		$sql= "SELECT * FROM reservations ORDER BY id DESC LIMIT $search_starting_limit_number, $this->get_num_result_per_page";
		$query= $this->mainDB->query($sql);
		$num_rows = $this->mainDB->numRows($query);


		$the_guest= array();
		$serial_number= NULL;

		if($num_rows!=0) {
			echo '
		<table cellspacing="0" cellpadding="8" width="100%">
			<tr class="fields">
				<th> S\N</th>
				<th>Full Name</th>
				<th>Room Number</th>
				<th>Room Type</th>
				<th>To Check In On</th>
				<th>Check Out</th>
				<th>Amount Deposited</th>
				<th>Phone</th>
				<th>Sex</th>
				<th>Payment Type</th>
			</tr>
			';
			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$the_guest[$row['id']] = new guest_singleton (['reservation_id' => $row['id']]);

				if (true) {
						$serial_number++;
						$animation_duration = ($serial_number*400>3000) ? 3000 : $serial_number*400;

						### BREAKING OUT
						?>
			<tr align="center" class="records"  data-aos="fade-in" data-aos-duration="<?php echo $animation_duration ?>">
				<td> <?php echo $serial_number ?> </td>
				<td><a href="javascript:window.open('includes/modal/manage-guest.php?reservation_id=<?php echo $row['id']; ?>&mdl_src=general-management', '', 'width=800,height=700')"><?php echo $the_guest[$row['id']]->data['full_name']  ?> </a></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_NUMBER']  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_TYPE']  ?></td>
				<td> <?php echo $the_guest[$row['id']]->data["CHECK_IN_DATE"] ?></td>
				<td><?php echo $the_guest[$row['id']]->data['CHECK_OUT_DATE']; ?></td>
				<td> <span id="currency-sign"><?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->data['amount_paid'])  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['phone'])  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['sex'][0]  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['payment_type'] ) ?></td>
			</tr>									

						<?php 
						### BREAKING IN
				}
		

			}
		echo '</table>'	;	
		$paginate_sql = "SELECT * FROM reservations";
		$paginate_query = $this->mainDB->query($paginate_sql);
		$paginate_numrows = $this->mainDB->numRows($paginate_query);
		$number_of_pages=ceil($paginate_numrows/$this->get_num_result_per_page); 	
		$this->display_pagination_links($number_of_pages);				
		} 
	}	

	public function relateGuestsToReservations() {
		$sql = "SELECT * FROM guests ";
		$query = $this->mainDB->query($sql);
		$the_guest = array();
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			if (!empty($row['reservation_id'])) {
				$the_guest[$row['reservation_id']] = new Guest_Singleton(["reservation_id" => $row['reservation_id']]);
				$the_guest[$row['reservation_id']]->reservationMapDomain($row['id']);
			}
		}

	}

	public function search ($value, $db_tbl, $num_result_per_page) {
		$value = sanitize_note($value);
		$db_tbl = sanitize_note($db_tbl);

		$this->get_num_result_per_page = $num_result_per_page;
		$search_starting_limit_number=($this->get_page_num-1) * $this->get_num_result_per_page;

		$sql = "SELECT * FROM $db_tbl WHERE full_name LIKE '%$value%' OR occupation LIKE '%$value%' OR sex LIKE '%$value%' OR home_address LIKE '%$value%' ORDER BY id DESC LIMIT $search_starting_limit_number, $this->get_num_result_per_page ";
		$query = $this->mainDB->query($sql);
		$num_rows= $this->mainDB->numRows($query);




		$the_guest = array();
		$serial_number=null;
		if ($db_tbl=='guests') :
				if ($num_rows!=0) {
								echo '
		<table cellspacing="0" cellpadding="8" width="100%">
			<tr class="fields">
				<th> S\N</th>
				<th>Full Name</th>
				<th>Room Number</th>
				<th>Room Type</th>
				<th>To Check In On</th>
				<th>Check Out</th>
				<th>Amount Deposited</th>
				<th>Refundable Amount</th>
				<th>Phone</th>
				<th>Sex</th>
			</tr>
			';	
					while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
					$serial_number++;
					$animation_duration = ($serial_number*400>3000) ? 3000 : $serial_number*400;
					$the_guest[$row['id']] = new Guest_Singleton(["guest_id" => $row['id']]);

					
			?>
			<tr align="center" class="records" data-aos="fade-in" data-aos-duration="<?php echo $animation_duration ?>">
				<td> <?php echo $serial_number ?> </td>
				<td><a href="javascript:window.open('includes/modal/manage-guest.php?guest_id=<?php echo $row['id']; ?>&mdl_src=general-management', '', 'width=800,height=700')"><?php echo $the_guest[$row['id']]->data['full_name']  ?> </a></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_NUMBER']  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_TYPE']  ?></td>
				<td><?php echo date('g:i a', strtotime($the_guest[$row['id']]->data["CHECK_IN_TIME"])) ?>, <?php echo $the_guest[$row['id']]->data["CHECK_IN_DATE"] ?></td>
				<td><?php if($the_guest[$row['id']]->checkedOutOnDueTime()) {echo $the_guest[$row['id']]->data['CHECK_OUT_DATE']; } if ($the_guest[$row['id']]->checkedOutEarly()) {echo $the_guest[$row['id']]->data['EARLY_CHECK_OUT_DATE']; }  if ($the_guest[$row['id']]->checkedOutlate()) {echo $the_guest[$row['id']]->data['LATE_CHECK_OUT_DATE']; }   ?></td>
				<td> <span id="currency-sign"><?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->data['amount_paid'])  ?></td>
				<td><span id="currency-sign"> <?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->REFUNDABLE_AMOUNT)  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['phone'])  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['sex'][0]  ?></td>
			</tr>									

		<?php
				}
		}  else {
					// not found
					include ($_SERVER['DOCUMENT_ROOT']."/includes/specifics/search_NOT-FOUND.php");
				}
			###BREAKING IN
			echo "</table>";
			$paginate_sql = "SELECT * FROM $db_tbl WHERE full_name LIKE '%$value%' OR occupation LIKE '%$value%' OR sex LIKE '%$value%' OR home_address LIKE '%$value%'";
			$paginate_query = $this->mainDB->query($paginate_sql);
			$paginate_numrows = $this->mainDB->numRows($paginate_query);
			$number_of_pages=ceil($paginate_numrows/$this->get_num_result_per_page); 	
			$this->display_pagination_links($number_of_pages);				

			endif;


		if ($db_tbl=='reservations') :
				if ($num_rows!=0) {
			echo '
		<table cellspacing="0" cellpadding="8" width="100%">
			<tr class="fields">
				<th> S\N</th>
				<th>Full Name</th>
				<th>Room Number</th>
				<th>Room Type</th>
				<th>To Check In On</th>
				<th>Check Out</th>
				<th>Amount Deposited</th>
				<th>Phone</th>
				<th>Sex</th>
				<th>Payment Type</th>
				<th> STAUS </th>
			</tr>
			';			

					while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
					$serial_number++;
					$animation_duration = ($serial_number*400>3000) ? 3000 : $serial_number*400;
					$the_guest[$row['id']] = new Guest_Singleton(["reservation_id" => $row['id']]);
					
			?>
			<tr align="center" class="records" data-aos="fade-in" data-aos-duration="<?php echo $animation_duration ?>">
				<td> <?php echo $serial_number ?> </td>
				<td><a href="javascript:window.open('includes/modal/manage-guest.php?reservation_id=<?php echo $row['id']; ?>&mdl_src=general-management', '', 'width=800,height=700')"><?php echo $the_guest[$row['id']]->data['full_name']  ?> </a></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_NUMBER']  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['ROOM_TYPE']  ?></td>
				<td> <?php echo $the_guest[$row['id']]->data["CHECK_IN_DATE"] ?></td>
				<td><?php echo $the_guest[$row['id']]->data['CHECK_OUT_DATE']; ?></td>
				<td> <span id="currency-sign"><?php echo $this->config->currency('sign') ?></span><?php echo number_format($the_guest[$row['id']]->data['amount_paid'])  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['phone'])  ?></td>
				<td><?php echo $the_guest[$row['id']]->data['sex'][0]  ?></td>
				<td><?php echo hyphenateIfNull($the_guest[$row['id']]->data['payment_type'] ) ?></td>
				<td> 

 				<?php if ($the_guest[$row['id']]->reservationIsCheckedIn()) : ?> <span class="" reservation-state="PROCESSED">PROCESSED</span> <?php endif ?>
  				<?php if ($the_guest[$row['id']]->reservationIsDue()) : ?> <span class="" reservation-state="DUE">DUE</span> <?php endif ?>			
				 <?php if ($the_guest[$row['id']]->reservationIsUnderDue()&&!$the_guest[$row['id']]->reservationIsCancelled()) : ?> <span class="" reservation-state="PENDING"> PENDING</span> <?php endif ?> 
				 <?php if ($the_guest[$row['id']]->reservationIsCancelled()) : ?> <span class="" reservation-state="CANCELLED">CANCELLED</span> <?php endif ?>
				  </td>
			</tr>									

		<?php
				}
			
		} 	else {
					// not found
					include ($_SERVER['DOCUMENT_ROOT']."/includes/specifics/search_NOT-FOUND.php");
				}
			###BREAKING IN
			echo "</table>";
			$paginate_sql = "SELECT * FROM $db_tbl WHERE full_name LIKE '%$value%' OR occupation LIKE '%$value%' OR sex LIKE '%$value%' OR home_address LIKE '%$value%'";
			$paginate_query = $this->mainDB->query($paginate_sql);
			$paginate_numrows = $this->mainDB->numRows($paginate_query);
			$number_of_pages=ceil($paginate_numrows/$this->get_num_result_per_page); 	
			$this->display_pagination_links($number_of_pages);				

			endif;

	}	

	public function countSearchResults ($value, $db_tbl) {
		$value = sanitize_note($value);
		$db_tbl = sanitize_note($db_tbl);

		$sql = "SELECT * FROM $db_tbl WHERE full_name LIKE '%$value%' OR occupation LIKE '%$value%' OR sex LIKE '%$value%' OR home_address LIKE '%$value%'  ";
		$query = $this->mainDB->query($sql);
	 	return $num_rows= $this->mainDB->numRows($query);
}	

	public function firstOne($par) {
		$sql = 'SELECT * FROM guests';
		$query = $this->mainDB->query($sql);
		$index = 0;
		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$index++;
			if ($index==1) {
				switch ($par) {
					case $par:
						$value=$row[$par];
						break;

					default:
						$value="<p style='color:red'> <b>'$par'</b> is a wrong value </p>";
						break;
				}		
			}				
			}
			return $value;
		}

	public function lastOne($par) {
		$sql = 'SELECT * FROM guests';
		$query = $this->mainDB->query($sql);
		$num_rows= $this->mainDB->numRows($query);
		$index = 0;
		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$index++;
			if ($index==$num_rows) {
				switch ($par) {
					case $par:
						$value=$row[$par];
						break;

					default:
						$value="<p style='color:red'> <b>'$par'</b> is a wrong value </p>";
						break;
				}		
			}				
			}
			return $value;
		}		

		
}
?>