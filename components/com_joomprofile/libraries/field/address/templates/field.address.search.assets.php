<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$key = !empty($data->field['params']['google_key']) ? "key=".$data->field['params']['google_key']."&" : "";
?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&<?php echo $key;?>libraries=places"></script>
<script>
	joomprofile.address = {};
	joomprofile.address.autocomplete = null;

	joomprofile.address.initialize = function(id) {
	  // Create the autocomplete object, restricting the search
	  // to geographical location types.
	  joomprofile.address.autocomplete = new google.maps.places.Autocomplete(
	      /** @type {HTMLInputElement} */(document.getElementById(id+'-address')),
	      { types: ['geocode'] });
	  // When the user selects an address from the dropdown,
	  // populate the address fields in the form.
	  google.maps.event.addListener(joomprofile.address.autocomplete, 'place_changed', function() {
	    joomprofile.address.fillInAddress(id);
	  });
	};

	// [START region_fillform]
	joomprofile.address.fillInAddress = function(id) {
	  // Get the place details from the autocomplete object.
	  var place = joomprofile.address.autocomplete.getPlace();

	  document.getElementById(id+'-latitude').value = '';
	  document.getElementById(id+'-longitude').value = '';
	  document.getElementById(id+'-formatted').value = '';
	  document.getElementById(id+'-latitude').value = place.geometry.location.lat();
	  document.getElementById(id+'-longitude').value = place.geometry.location.lng();
	  document.getElementById(id+'-formatted').value = place.formatted_address;
	}
	// [END region_fillform]

	// [START region_geolocation]
	// Bias the autocomplete object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	joomprofile.address.geolocate = function() {
	  if (navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition(function(position) {
	      var geolocation = new google.maps.LatLng(
	          position.coords.latitude, position.coords.longitude);
	      var circle = new google.maps.Circle({
	        center: geolocation,
	        radius: position.coords.accuracy
	      });
	      joomprofile.address.autocomplete.setBounds(circle.getBounds());
	    });
	  }
	}
	// [END region_geolocation]

</script>
<?php 
