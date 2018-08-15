<?php

/**
*	@author Clinton Nzedimma, Paul Princewill (c) Novacom Webs Nigeria 2018
*	@package  Tesseract Hotel Management System
*	@subpackage Administration
* This class contains methods and and properties for rooms.
*/


class Room 
{
	private $mainDB;

	public function __construct()
	{
		$this->mainDB=new sqlite_DB($_SERVER['DOCUMENT_ROOT']."/engine/databases/main.DB");
	}

	public function addNew($input_roomType, $input_roomNumber, $input_roomPrice)
	{
		$input_roomType=sanitize_note($input_roomType);
		$input_roomNumber=sanitize_note($input_roomNumber);
		$input_roomPrice=sanitize_note($input_roomPrice);

		$hour=date('H'); // hour of post in 24 hour format
		$minute=date('i'); //minute 
		$second=date('s'); // second	
		$date_no=date('d'); //date 
		$month=date('m'); // month 
		$year=date('Y'); //year 

		$room_status="VACANT"; // room status is vacant for every new room


		$sql="INSERT INTO rooms (
			id,
			room_number, 
			type,
			price,
			day_of_add,
			month_of_add,
			year_of_add,
			hour_of_add,
			minute_of_add,
			status
		) 
		VALUES (
			NULL,
			'$input_roomNumber',
			'$input_roomType',
			'$input_roomPrice',
			'$date_no',
			'$month',
			'$year',
			'$hour',
			'$minute',
			'$room_status'
		)";
		$query=$this->mainDB->query($sql);
	
	}


	public function modifyById ($get_room_id, $input_roomType, $input_roomNumber, $input_roomPrice) {
		$get_room_id= sanitize_note($get_room_id);
		$input_roomType=sanitize_note($input_roomType);
		$input_roomNumber=sanitize_note($input_roomNumber);
		$input_roomPrice=sanitize_note($input_roomPrice);

		//modify date below
		$hour=date('H'); // hour of post in 24 hour format
		$minute=date('i'); //minute 
		$second=date('s'); // second	
		$date_no=date('d'); //date 
		$month=date('m'); // month 
		$year=date('Y'); //year 

		$sql="UPDATE rooms SET
			type='$input_roomType',
			room_number='$input_roomNumber',
			price='$input_roomPrice',
			hour_of_modif='$hour',
			minute_of_modif='$minute',
			day_of_modif='$date_no',
			month_of_modif='$month',
			year_of_modif='$year'
		 WHERE id='$get_room_id' ";

		$query=$this->mainDB->query($sql);	 
}

	public function displayAllByTable () {
		$sql='SELECT * FROM rooms ';
		$query=$this->mainDB->query($sql);
		$num_rows= $this->mainDB->numRows($query);

		$serial_number_count=NULL;

			echo 
'<table cellspacing="0" cellpadding="10">
	<tr class="fields"> <th>S/N</th> <th>Room Number</th> <th>Type</th> <th>Price</th> <th>Added On</th> <th>Last Modified On</th>   <th>STATUS</th></tr>
	';	
		if ($num_rows!=0) {

			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$serial_number_count++;
				# BREAKING OUT
				?>	
	

	<tr align="center" class="records"> 
		<td id='serial_number'><?php echo $serial_number_count; ?></td> 
		<td id='room_number'><?php echo $row['room_number']; ?></td> 
		<td id='room_type'><?php echo $row['type']; ?></td> 
		<td id='room_price'><?php echo "<span id='currency-sign'>".config::currency('sign')."</span>".number_format($row['price']); ?></td>
		<td id='room_add_time'> <?php echo pad_zero_before_digit($row['hour_of_add']).":".pad_zero_before_digit($row['minute_of_add']). " - " .$row['day_of_add']. " ".substr(time_object::integer_to_month( $row['month_of_add']), 0, 3). " " .$row['year_of_add'];  ?></td>
	<?php
		if ($this->wasModifiedById($row['id'])) {
			?>
		<td id='room_modif_time'> <?php echo pad_zero_before_digit($row['hour_of_modif']).":".pad_zero_before_digit($row['minute_of_modif']). " - " .$row['day_of_modif']. " ". substr(time_object::integer_to_month($row['month_of_modif']), 0, 3). " " .$row['year_of_modif'];  ?></td>
			<?php
		} else {
			echo "<td id='room'>NEVER </td>";
		}
	?>	
		
		<td id='room_status'><?php echo $row['status']; ?></td> 
	</tr>
				<?php		
				# BREAKING IN
			}
echo 
"</table>";			
		}
	}





	public function displayAllForEdit () {
		$sql='SELECT * FROM rooms WHERE status="VACANT" ';
		$query=$this->mainDB->query($sql);
		$num_rows= $this->mainDB->numRows($query);
		$serial_number_count=NULL;

			echo 
'<table cellspacing="0" cellpadding="10">
	<tr class="fields"> <th>S/N</th> <th>Room Number</th> <th>Type</th> <th>Price</th>  </tr>
	';	
		if ($num_rows!=0) {

			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$serial_number_count++;
				# BREAKING OUT
				?>	
	

	<tr align="center" class="records"> 
		<td id='serial_number'><?php echo $serial_number_count; ?></td> 
		<td id='room_number'><a href="?edit=<?php echo $row['id']; ?>"><?php echo $row['room_number']; ?></a></td> 
		<td id='room_type'><?php echo $row['type']; ?></td> 
		<td id='room_price'><?php echo "<span id='currency-sign'>".config::currency('sign')."</span>".number_format($row['price']); ?></td>
	</tr>
				<?php		
				# BREAKING IN
			}
echo 
"</table>";			
		}
	}




	public function displayAllByGrid () {
		$sql='SELECT * FROM rooms ';
		$query=$this->mainDB->query($sql);
		$num_rows= $this->mainDB->numRows($query);

		$serial_number_count=NULL;


		if ($num_rows!=0) {

			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$serial_number_count++;
				$animation_duration= ($serial_number_count*400>3000) ? 3000 : $serial_number_count*400;

				# BREAKING OUT
				?>	
<span class="rooms" data-aos="fade-up" data-aos-duration="<?php echo $animation_duration; ?>" room-state="<?php echo $row['status']?>">
	<p id="room-Number"><?php echo $row['room_number'] ?></p>
	<p id="room-Type"> <?php echo $row['type'] ?></p>
</span>
	<?php		
				# BREAKING IN
			}
			
		}
	}



	public function displayAllForDelete () {
		$sql='SELECT * FROM rooms WHERE status="VACANT" ';
		$query=$this->mainDB->query($sql);
		$num_rows= $this->mainDB->numRows($query);

		$serial_number_count=NULL;

			echo 

'<form action="" method="POST">	<table cellspacing="0" cellpadding="10">
	<tr class="fields"> <th> </th> <th>S/N</th> <th>Room Number</th> <th>Type</th> <th>Price</th></tr>
	';	
		if ($num_rows!=0) {

			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$serial_number_count++;
				# BREAKING OUT
				?>	
	

	<tr align="center" class="records"> 
		<td><input type="checkbox" name="<?php echo $row['id'] ?>"></td>
		<td id='serial_number'><?php echo $serial_number_count; ?></td> 
		<td id='room_number'><?php echo $row['room_number']; ?></td> 
		<td id='room_type'><?php echo $row['type']; ?></td> 
		<td id='room_price'><?php echo "<span id='currency-sign'>".config::currency('sign')."</span>".number_format($row['price']); ?></td>
	</tr>
				<?php		
				# BREAKING IN
			}
echo 
"</table> <input type='submit' value='Delete Selected' name='room-delete-submit'/> </form>";			
		}
	}




	public function numberExists($input_roomNumber)
	{
		$input_roomNumber=sanitize_note($input_roomNumber);
		$sql="SELECT * FROM rooms WHERE room_number= '$input_roomNumber' ";
		$query=$this->mainDB->query($sql);
		$num_rows=$this->mainDB->numRows($query);

		if ($num_rows!=0) {
			return true;
		}
	}

	public function numberById($input_id)
	{	
		$input_id=sanitize_note($input_id);
		$sql="SELECT room_number FROM rooms WHERE id='$input_id'";
		$query=$this->mainDB->query($sql);
		$num_rows=$this->mainDB->numRows($query);

		if ($num_rows!=0) {
			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				$value=$row['room_number'];
			}
			return $value;
		}
	}




	


	public function getDataByNumber($input_roomNumber, $par)
	{
		$input_roomNumber=sanitize_note($input_roomNumber);
		$sql="SELECT * FROM rooms WHERE room_number='$input_roomNumber' ";
		$query=$this->mainDB->query($sql);
		$num_rows=$this->mainDB->numRows($query);

		if ($num_rows!=0){
			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				switch ($par) {
					case 'id':
						$value=$row['id'];	
						break;

					case 'type':
						$value=$row['type'];
						break;

					case 'number':
						$value=$row['room_number'];	
						break;

					case 'price':
						$value=$row['price'];
						break;	

					case 'status':
						$value=$row['status'];			
						break;			
					
					default:
						$value="<p style='color:red'> <b>'$par'</b> is a wrong value </p>";
				}
				 return $value;
			}
		}
	}


	public function getDataById($input_ID, $par)
	{
		$input_ID=sanitize_note($input_ID);
		$sql="SELECT * FROM rooms WHERE id='$input_ID' ";
		$query=$this->mainDB->query($sql);
		$num_rows=$this->mainDB->numRows($query);

		if ($num_rows!=0){
			while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
				switch ($par) {
					case 'id':
						$value=$row['id'];	
						break;

					case 'type':
						$value=$row['type'];
						break;

					case 'number':
						$value=$row['room_number'];	
						break;

					case 'price':
						$value=$row['price'];
						break;	

					case 'status':
						$value=$row['status'];			
						break;			
					
					default:
						$value="<p style='color:red'> <b>'$par'</b> is a wrong value </p>";
				}
				 return $value;
			}
		}
	}






public function wasModifiedById ($get_id) {
	$get_id=sanitize_note($get_id);
	$sql="SELECT * FROM rooms WHERE id='$get_id' ";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	
	if ($num_rows!=0) {
		$row=$query->fetchArray(SQLITE3_ASSOC);
			if (empty($row['hour_of_modif']) && empty($row['minute_of_modif']) && empty($row['second_of_modif']) && empty($row['day_of_modif']) && empty($row['month_of_modif']) && empty($row['year_of_modif'])) {
				//not modifed
				return FALSE;
			} else {
				//modified
				return TRUE;
			}

		
		
	}


}


public function deleteById ($get_id) {
	$get_id=sanitize_note($get_id);
	$sql="DELETE FROM rooms WHERE id= '$get_id'";
	$query=$this->mainDB->query($sql);
}	




public function countAll() {
	//this function returns the total number of rooms
	$sql="SELECT * FROM rooms";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	return $num_rows;
}

public function countWhere($par) {
	// this function returns the total number of rooms that are vacant, reserved or lodged
	$par=sanitize_note(strtoupper($par)); //input status
	$sql="SELECT * FROM rooms WHERE status='$par'";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	return $num_rows;	

}


public function optionsOfAllRooms($input_name) {
	/**
	* @param input name is name of input form
	*/ 

	$sql="SELECT * FROM rooms";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	if ($num_rows!=0) {
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			# BREAKING OUT
			?>

<option value="<?php echo $row['id'] ?>" <?php selectPostConst($input_name, $row['id']); ?> > ROOM  <?php echo $row['room_number'] ?> </option>

			<?php
			# BREAKING IN
		}
	}
}

public function optionsOfVacantRooms($input_name) {
	/**
	* @param input name is name of input form
	*/ 
	$sql="SELECT * FROM rooms WHERE status='VACANT' ";
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	if ($num_rows!=0) {
		while ($row=$query->fetchArray(SQLITE3_ASSOC)) {
			# BREAKING OUT
			?>

<option value="<?php echo $row['id'] ?>" <?php selectPostConst($input_name, $row['id']); ?> > ROOM  <?php echo $row['room_number'] ?> </option>

			<?php
			# BREAKING IN
		}
	}
}




public function setStatusById($input_id, $room_status) {
		$input_id= sanitize_note($input_id);
		$room_status= sanitize_note($room_status);	

		//if input room status values are vacant, resevred or lodged
		if ($room_status=="VACANT" || $room_status=="RESVERVED" || $room_status=="LODGED") {
			$sql="UPDATE rooms SET status='$room_status'  WHERE id='$input_id' ";
			$query=$this->mainDB->query($sql);
		}
}

public function isLodgedById($input_id) {
	$input_id= sanitize_note($input_id);
	$sql="SELECT * FROM rooms WHERE id='$input_id' AND status='LODGED'" ;
	$query=$this->mainDB->query($sql);
	$num_rows=$this->mainDB->numRows($query);

	if($num_rows!=0) {
		return true;
	}

}




}
?>