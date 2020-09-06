let map;
let position;

function initMap() {
	// const position = {lat: -2.124587, lng: -79.890574};

	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 14,
		disableDefaultUI: true,
		zoomControl: true
  });
}