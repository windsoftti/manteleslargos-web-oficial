$(window).on('load', () => initMultipleMap());

function initMultipleMap() {
  return new Promise((resolve, reject) => {
    $('.map').each(function () {
      var $markers = [];

      const mapContainer = $(this).children('div');
      const latitude = mapContainer.attr('latitude');
      const longitude = mapContainer.attr('longitude');
      const mapZoom = mapContainer.attr('zoom');

      console.log(mapZoom);

      const mapId = mapContainer.attr('id');

      let lat = 16.750917060666666;
      let lng = -93.14536588206437;
      var zoom = 13;

      if ((latitude != undefined && longitude != undefined) && (latitude != '' && longitude != '')) {
        lat = parseFloat(latitude);
        lng = parseFloat(longitude);
        zoom = 11;

        if (mapZoom != undefined && mapZoom != '') zoom = parseInt(mapZoom);
      }

      const map = new google.maps.Map(document.getElementById(mapId), {
        zoom: zoom,
        center: {
          lat,
          lng
        }
      });

      const marker = new google.maps.Marker({
        position: {
          lat,
          lng
        },
        map: map,
        draggable: true,
      });

      marker.addListener('dragend', function (event) {
        const coordinates = {
          latitude: this.getPosition().lat(),
          longitude: this.getPosition().lng()
        };

        getMapAddress({
          coordinates,
          mapId
        });
      });

      $markers[mapId] = [];

      const input = document.getElementById(`search-${mapId}`);
      console.log(input)
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

            getMapAddress({
              coordinates,
              mapId
            });
          });
        });

        map.fitBounds(bounds);
      });
    });

    resolve(true);
  });
}

async function getMapAddress({
  coordinates,
  mapId
}) {
  showPageLoading();

  const { latitude, longitude } = coordinates;

  const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${latitude},${longitude}&key=${GOOGLE_MAPS_API_KEY}`;

  const resData = await fetch(url);
  const response = await resData.json();

  const address = response.results[0].formatted_address;

  $(`#latitude-${mapId}`).val(latitude);
  $(`#longitude-${mapId}`).val(longitude);
  $(`#address-${mapId}`).val(address);

  hidePageLoading();
}