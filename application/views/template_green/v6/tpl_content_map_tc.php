<?php
	echo $Content1;
	echo $Content2;
	echo $Content3;
?>

*******

<p>請選擇提貨點</p>
<p>城市:<select name="collection_point_state_id" id="collection_point_state_id">
<option value="0">--- 請選擇 ---</option>
<option value="1">香港特別行政區</option>
</select>
</p>
<p>地區:
<select name="collection_point_city_id" id="collection_point_city_id">
<option value="0">--- 請選擇 ---</option>
</select>
</p>

<p>提貨點:
<select name="shop_selection_multi_layers_shop_dd" id="shop_selection_multi_layers_shop_dd">
<option value="0">--- 請選擇 ---</option>
</select>
</p>

<div id="nothing" style="display:none"></div>

<div id="map_canvas" style="height:500px;top:30px"></div>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">

var geocoder;
var map;
var x = 2;
var gmarkers = [];

$('#collection_point_state_id').change(function() {
	$("#collection_point_city_id").load('<?php echo site_url("collectionshop/loadcity");?>/'+$(this).val());
	$("#shop_selection_multi_layers_shop_dd").load('<?php echo site_url("collectionshop/loadshopcity/0");?>');
});

$('#collection_point_city_id').change(function() {
	$("#shop_selection_multi_layers_shop_dd").load('<?php echo site_url("collectionshop/loadshopcity");?>/'+$(this).val());
});

$('#shop_selection_multi_layers_shop_dd').change(function() {
	loadAddress($(this).val());
});

$(document).ready(function() {
	geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(22.397696, 114.19833);
	var myOptions = {
		zoom: 16,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	
	geocoder.geocode( { 'address': 'Unit B, 8/F, 15-29 Wo Shui Street, Fotan'}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK)
		{
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map, 
				position: results[0].geometry.location,
			});
			gmarkers.push(marker);

			var infoWindow = new google.maps.InfoWindow;
			infoWindow.setContent('Unit B, 8/F, 15-29 Wo Shui Street, Fotan');
			infoWindow.open(map, marker);
			
			var onMarkerClick = function() {
				infoWindow.open(map, marker);
			};
			
			google.maps.event.addListener(marker, 'click', onMarkerClick);
			
		}
		else
		{
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
	
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
});



function loadAddress(address_id)
{
	$("#nothing").load('<?php echo site_url("collectionshop/loadaddress");?>/'+address_id, function(result){
		codeAddress(result);
	});
}

function codeAddress(address)
{
	// var address = document.getElementById("shop_selection_multi_layers_shop_dd").value;
	geocoder.geocode( { 'address': address}, function(results, status) {
		
		if (status == google.maps.GeocoderStatus.OK)
		{
			for (var i=0; i<gmarkers.length; i++) {
				gmarkers[i].setMap(null);
			}
			
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map, 
				position: results[0].geometry.location
			});
			gmarkers.push(marker);
			
			var infoWindow = new google.maps.InfoWindow;
			infoWindow.setContent(address);
			infoWindow.open(map, marker);
			
			var onMarkerClick = function() {
				infoWindow.open(map, marker);
			};
			
			google.maps.event.addListener(marker, 'click', onMarkerClick);
		}
		else
		{
			alert("Address: "+address);
		}
	});
}

</script>