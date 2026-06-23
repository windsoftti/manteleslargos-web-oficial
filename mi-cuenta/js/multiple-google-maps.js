const $createMapComponent = async (mapId) => {
  var data = [];

  const existData = $('#invitation-encode-data').length;

  if (existData) data = JSON.parse(atob($('#invitation-encode-data').val()));

  const component = `
    <span class="mt-5"><b>INDICA LA UBICACIÓN EXACTA</b></span>
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="form-group">
          <label class="mb-0" for="pac-input-${mapId}"><span style="color: red;"><b>¡IMPORTANTE!</b></span> Arrastra el marcador hasta la ubicación del lugar ó buscalo aqui colocando el nombre o su dirección completa.</label>
          <div class="input-group">
            <input type="text" id="pac-input-${mapId}" name="pac-input" class="form-control" placeholder="Buscar lugar">
          </div>
        </div>
      </div>
    </div>

    <input type="hidden" name="latitude${mapId}" id="latitude-${mapId}" value="${data[`latitude${mapId}`] ? data[`latitude${mapId}`] : ''}">
    <input type="hidden" name="longitude${mapId}" id="longitude-${mapId}" value="${data[`longitude${mapId}`] ? data[`longitude${mapId}`] : ''}">

    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="p-0 table-bordered d-flex align-items-center justify-content-center rounded" style="min-height: 200px">
          <div id="${mapId}" style="width:100%;height: 300px;" class="d-flex align-items-center justify-content-center">
            <p>
              <i class="fas fa-map nav-icon fa-7x text-gray"></i>
              <br>
              Cargando mapa...
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 mb-2">
        <label class="mb-0" for="address-${mapId}">Dirección</label>
        <textarea id="address-${mapId}" class="form-control" rows="4" name="address${mapId}">${data[`address${mapId}`] ? data[`address${mapId}`] : ''}</textarea>
      </div>
    </div>
  `;

  await $(`#${mapId}`).replaceWith(component);

  if (!existData) {
    $initMap({ mapId });
  }

  if (existData) {
    if (existData[`latitude${mapId}`] && existData[`longitude${mapId}`]) {
      const coords = {
        lat: parseFloat(data[`latitude${mapId}`]),
        lng: parseFloat(data[`longitude${mapId}`])
      }

      $initMap({
        mapId,
        coords
      });
    } else {
      $initMap({ mapId });
    }
  }
}

var $mapMarker = [];
var $markers = [];

function $initMap({ mapId, coords = null }) {
  const initialCoords = coords ? coords : {
    lat: 16.750917060666666,
    lng: -93.14536588206437
  };

  var map = new google.maps.Map(document.getElementById(mapId), {
    center: {
      lat: initialCoords.lat,
      lng: initialCoords.lng
    },
    zoom: 13,
    mapTypeId: "roadmap",
  });

  $mapMarker[mapId] = new google.maps.Marker({
    map: map,
    draggable: true,
    animation: google.maps.Animation.DROP,
    position: new google.maps.LatLng(
      initialCoords.lat,
      initialCoords.lng
    )
  });

  $mapMarker[mapId].addListener('dragend', function (event) {
    const coordinates = {
      latitude: this.getPosition().lat(),
      longitude: this.getPosition().lng()
    };

    $getAddress({
      coordinates,
      mapId
    });
  });

  $markers[mapId] = [];

  const input = document.getElementById(`pac-input-${mapId}`);
  const searchBox = new google.maps.places.SearchBox(input);

  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    const markers = $markers[mapId];

    markers.forEach((marker) => {
      marker.setMap(null);
    });

    $markers[mapId] = [];

    const bounds = new google.maps.LatLngBounds();
    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }

      const icon = {
        url: place.icon,
        //size: new google.maps.Size(71, 71),
        //color: 'blue',
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };

      // Create a marker for each place.
      $markers[mapId].push(
        new google.maps.Marker({
          map,
          //icon,
          title: place.name,
          draggable: true,
          position: place.geometry.location,
        })
      );

      console.log(place.formatted_address);

      const coordinates = {
        latitude: place.geometry.location.lat(),
        longitude: place.geometry.location.lng()
      };

      $(`#latitude-${mapId}`).val(coordinates.latitude);
      $(`#longitude-${mapId}`).val(coordinates.longitude);
      $(`#address-${mapId}`).val(place.formatted_address);

      if (place.geometry.viewport) {
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });

    console.log($markers[mapId]);

    $markers[mapId].length > 0 && $markers[mapId].map(async function (item, index) {
      const markers = $markers[mapId];

      markers[index].addListener('dragend', function (event) {
        const coordinates = {
          latitude: this.getPosition().lat(),
          longitude: this.getPosition().lng()
        };

        const aaaaaaaa = this.getPosition();
        console.log(aaaaaaaa);

        $getAddress({
          coordinates,
          mapId
        });
      });
    });

    map.fitBounds(bounds);
  });
}

async function $getAddress({ coordinates, mapId }) {
  showPageLoading();

  const { latitude, longitude } = coordinates;

  const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${latitude},${longitude}&key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE`;

  const resData = await fetch(url);
  const response = await resData.json();

  const address = response.results[0].formatted_address;

  $(`#latitude-${mapId}`).val(latitude);
  $(`#longitude-${mapId}`).val(longitude);
  $(`#address-${mapId}`).val(address);

  hidePageLoading();
}