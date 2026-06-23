var googleMaps;
var googleMarkers = [];

var googleMapsStyle = [
  {
    featureType: "poi",
    elementType: "labels",
    stylers: [
      { visibility: "off" }
    ]
  }
];

// Initialize and add the map
function initMap() {
  const latitude = parseFloat($('#business-info').attr('data-latitude'));
  const longitude = parseFloat($('#business-info').attr('data-longitude'));

  const coords = { lat: latitude, lng: longitude };

  console.log(coords)

  googleMaps = new google.maps.Map(document.getElementById("map"), {
    zoom: 13.5,
    center: coords,
    styles: googleMapsStyle
  });

  googleMarkers = new google.maps.Marker({
    position: coords,
    map: googleMaps,
    icon: `${BASE_URL}/src/assets/images/marcador.png`
  });
}

window.initMap = initMap;