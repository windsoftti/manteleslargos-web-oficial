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
  const coords = { lat: 16.75322, lng: -93.11103 };

  googleMaps = new google.maps.Map(document.getElementById("map"), {
    zoom: 4,
    center: coords,
    styles: googleMapsStyle
  });
}

/* ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//-- BUSINESS MAP
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
function initBusinessesMap(div = '.listing-item') {
  const coords = { lat: 16.75322, lng: -93.11103 };

  googleMaps = new google.maps.Map(document.getElementById("map"), {
    zoom: 13.5,
    center: coords,
    styles: googleMapsStyle
  });

  let count = 0;

  $(div).map(function () {
    const data = JSON.parse(atob($(this).attr('data-business')));

    if (count == 0) {
      const center = new google.maps.LatLng(parseFloat(data.Latitud), parseFloat(data.Longitud));
      googleMaps.panTo(center);
    }

    if (data.Latitud && data.Longitud) {
      const coordinates = {
        lat: parseFloat(data.Latitud),
        lng: parseFloat(data.Longitud)
      }

      googleMarkers[data.idSalon] = new google.maps.Marker({
        position: coordinates,
        map: googleMaps,
        icon: `https://manteleslargos.com/2021/web/images/marcador.png`
      });
    }

    count = count + 1;
  });

  $(div).hover(function () {
    const data = JSON.parse(atob($(this).attr('data-business')));

    if (data.Latitud && data.Longitud) {
      const center = new google.maps.LatLng(parseFloat(data.Latitud), parseFloat(data.Longitud));
      googleMaps.panTo(center);

      googleMarkers[data.idSalon].setAnimation(google.maps.Animation.BOUNCE);
    }
  }, function () {
    const data = JSON.parse(atob($(this).attr('data-business')));

    if (data.Latitud && data.Longitud) {
      googleMarkers[data.idSalon].setAnimation(null);
    }
  });
}

window.initMap = initMap;