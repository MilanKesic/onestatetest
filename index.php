<?php 

require_once 'helperFunc.php';

?>
<!DOCTYPE html>
<html>
<head>
	<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<title>OnState Test Project</title>
	<script src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false" type="text/javascript"></script>
	<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h3>Search closest location</h3>
	<div class="searchFormWrapper">
		<form>
			<input id="address" type="text" value="Bradford, UK"> 
			<input onclick="codeAddress();" type="button" value="Search">
		</form>
	</div>
	<div id="info"></div>
	<div id="map" style="height: 500px; width:500px"></div>
	<div id="loc_sorted"></div>
	<div class="form-wrapper">
		<h3>Add new location:</h3>
		<form action="insertLoc.php" id="insertForm" method="post" name="insertForm">
			<label>Location</label> <input id="location" name="location" type="text">
			<input name="submit" type="submit" value="Submit">
		</form>
	</div>
	<?php 
	  $locations = getLocations();
	?>
	<script type="text/javascript">
	 var locations = [
	   <?php for ($i=0;$i<count($locations);$i++) { ?>
	    ['<?php echo $locations[$i]['loc_name']; ?>', <?php echo $locations[$i]['loc_lat']; ?>, <?php echo $locations[$i]['loc_long']; ?>, <?php echo (count($locations)-$i) ?>],
	   <?php } ?>
	 ];
	 var geocoder = null;
	 var map = null;
	 var customerMarker = null;
	 var gmarkers = [];
	 var closest = [];

	 function initialize() {
	     geocoder = new google.maps.Geocoder();
	     map = new google.maps.Map(document.getElementById('map'), {
	         mapTypeId: google.maps.MapTypeId.ROADMAP
	     });
	     var infowindow = new google.maps.InfoWindow();
	     var marker, i;
	     var bounds = new google.maps.LatLngBounds();
	     for (i = 0; i < locations.length; i++) {
	         marker = new google.maps.Marker({
	             position: new google.maps.LatLng(locations[i][1], locations[i][2]),
	             map: map
	         });
	         gmarkers.push(marker);
	         //extend the bounds to include each marker's position
	         bounds.extend(marker.position);
	         google.maps.event.addListener(marker, 'click', (function(marker, i) {
	             return function() {
	                 infowindow.setContent(locations[i][0]);
	                 infowindow.open(map, marker);
	             }
	         })(marker, i));
	     }
	     map.fitBounds(bounds);
	 }

	 function codeAddress() {
	     var address = document.getElementById('address').value;
	     geocoder.geocode({'address': address}, function(results, status) {
	         if (status == google.maps.GeocoderStatus.OK) {
	             map.setCenter(results[0].geometry.location);
	             if (customerMarker) customerMarker.setMap(null);
	             var image = 'http://maps.google.com/mapfiles/ms/icons/blue.png';
	             customerMarker = new google.maps.Marker({
	                 map: map,
	                 position: results[0].geometry.location,
	                 icon: image
	             });
				//console.log(results[0]);
	             customerMarker.info = new google.maps.InfoWindow({
            	 	content: results[0].formatted_address
            	 });

            	 google.maps.event.addListener(customerMarker, 'click', function() {
            	 	customerMarker.info.open(map, customerMarker);
            	 });
            	 
	             closest = findClosestN(results[0].geometry.location, gmarkers.length);
	             
	             // get driving distance
	             //closest = closest.splice(0, gmarkers.length);
	             calculateDistances(results[0].geometry.location, closest, closest.length);
	         } else {
	             alert(
	                 'Geocode was not successful for the following reason: ' +
	                 status);
	         }
	     });
	 }

	 function findClosestN(pt, numberOfResults) {
	     var closest = [];
	        //document.getElementById('info').innerHTML += "processing "+gmarkers.length+"<br>";
	        for (var i=0; i<gmarkers.length;i++) {
	          gmarkers[i].distance = google.maps.geometry.spherical.computeDistanceBetween(pt,gmarkers[i].getPosition());
	          //document.getElementById('info').innerHTML += "process "+i+":"+gmarkers[i].getPosition().toUrlValue(6)+":"+gmarkers[i].distance.toFixed(2)+"<br>";
	          gmarkers[i].setMap(null);
	          closest.push(gmarkers[i]);
	        }
	        
	        closest.sort(sortByDist);
	        return closest;
	 }

	 function sortByDist(a, b) {
	     return (a.distance - b.distance)
	 }

	 function calculateDistances(pt, closest, numberOfResults) {
	     var service = new google.maps.DistanceMatrixService();
	     var request = {
	         origins: [pt],
	         destinations: [],
	         travelMode: google.maps.TravelMode.DRIVING,
	         unitSystem: google.maps.UnitSystem.METRIC,
	         avoidHighways: false,
	         avoidTolls: false
	     };

	     for (var i = 0; i < closest.length; i++) { 
	         request.destinations.push(closest[i].getPosition());
	     }
	     service.getDistanceMatrix(request, function(response, status) {
	         if (status != google.maps.DistanceMatrixStatus.OK) {
	             alert('Error was: ' + status);
	         } else {
	             var origins = response.originAddresses;
	             var destinations = response.destinationAddresses;
	             var outputDiv = document.getElementById('loc_sorted');
	             outputDiv.innerHTML = '';
	             //console.log(response);
	             var results = response.rows[0].elements;
	             for (var i = 0; i < numberOfResults; i++) {
	            	 //just closest location on map
	                 if (i < 1) {
	                 	closest[i].setMap(map);
	                 }
	                 outputDiv.innerHTML +=
	                	 destinations[i] + '<br>' +
	                     results[i].distance.text + ' appoximately ' +
	                     results[i].duration.text + '<br><hr>';
	             }
	         }
	     });
	 }
	 google.maps.event.addDomListener(window, 'load', initialize);
	 
	</script>
</body>
</html>