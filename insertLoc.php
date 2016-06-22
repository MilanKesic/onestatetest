<?php 
require_once 'helperFunc.php';

if (isset($_POST['submit'])) {

	$db = new Db();
	
	//Quote and escape form submitted values
	$locationName = $db->quote($_POST['location']);
	
	// get latitude, longitude and formatted address
	$data_arr = geocode($_POST['location']);
	
	// if able to geocode the address
	if($data_arr){
		 
		$lat = $data_arr[0];
		$long = $data_arr[1];
		$formatted_address = $db->quote($data_arr[2]);
		
		//echo $formatted_address;
		
		$result = $db -> query("INSERT INTO `locations` (`loc_name`, `loc_lat`, `loc_long`) VALUES (" . $formatted_address . ", '" . $lat. "', '" . $long. "')");
		
		if ($result !== false) {
			header("Location: " . $_SERVER['HTTP_REFERER']);
		} else {
			echo "No location was added try again!";
			echo $db->error();
		}
	} else {
		echo "Location couldn't be geolocated. Try again.";
	}

}