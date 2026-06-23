var mapMarker = [];
var markers = [];

function setMapa() {
  var coords = null;
  const latitud = $('#latitud').val();
  const longitud = $('#longitud').val();

  if (latitud && longitud) coords = {
    lat: latitud,
    lng: longitud
  }

  if (coords) {
    var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 12,
      center: new google.maps.LatLng(
        coords.lat,
        coords.lng
      ),
      zoom: 13,
      mapTypeId: "roadmap",
    });

    mapMarker = new google.maps.Marker({
      map: map,
      draggable: true,
      animation: google.maps.Animation.DROP,
      position: new google.maps.LatLng(
        coords.lat,
        coords.lng
      )
    });
  }

  if (coords == null) {
    var map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: 16.750917060666666, lng: -93.14536588206437 },
      zoom: 13,
      mapTypeId: "roadmap",
    });

    mapMarker = new google.maps.Marker({
      map: map,
      draggable: true,
      animation: google.maps.Animation.DROP,
      position: new google.maps.LatLng(
        16.750917060666666,
        -93.14536588206437
      )
    });
  }

  mapMarker.addListener('dragend', function (event) {
    const coordinates = {
      latitude: this.getPosition().lat(),
      longitude: this.getPosition().lng()
    };

    getAddress(coordinates);
  });

  // Create the search box and link it to the UI element.
  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);
  //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];
    // For each place, get the icon, name and location.
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
      markers.push(
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

      //getAddress(coordinates);
      $('#latitud').val(coordinates.latitude);
      $('#longitud').val(coordinates.longitude);
      $('#direccion').val(place.formatted_address);
      $('.direccion-message').html('<p><span style="color: red; margin-top:15px;">¡IMPORTANTE! </span>Escribe la dirección de tu negocio.</p>');

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });

    console.log(markers);

    markers.length > 0 && markers.map(async function (item, index) {
      console.log(index);
      markers[index].addListener('dragend', function (event) {
        const coordinates = {
          latitude: this.getPosition().lat(),
          longitude: this.getPosition().lng()
        };

        const aaaaaaaa = this.getPosition();
        console.log(aaaaaaaa);

        getAddress(coordinates);
      });
    });

    map.fitBounds(bounds);
  });
}

async function getAddress(coordinates) {
  showPageLoading();

  const { latitude, longitude } = coordinates;

  const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${latitude},${longitude}&key=AIzaSyBcXIXiRSirvWVofs7wRolh-WjSSUF4jIE`;

  const resData = await fetch(url);
  const response = await resData.json();

  const address = response.results[0].formatted_address;

  $('#latitud').val(latitude);
  $('#longitud').val(longitude);
  $('.direccion-message').html('<p><span style="color: red; margin-top:15px;">¡IMPORTANTE! </span>Escribe la dirección del salón.</p>');
  $('#direccion').val(address);

  hidePageLoading();
}

function changeMapa(coords = null) {
  if (coords) {
    var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 12,
      center: new google.maps.LatLng(
        coords.lat,
        coords.lng
      ),
      zoom: 13,
      mapTypeId: "roadmap",
    });

    mapMarker = new google.maps.Marker({
      map: map,
      draggable: true,
      animation: google.maps.Animation.DROP,
      position: new google.maps.LatLng(
        coords.lat,
        coords.lng
      )
    });
  }

  if (coords == null) {
    var map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: 16.750917060666666, lng: -93.14536588206437 },
      zoom: 13,
      mapTypeId: "roadmap",
    });

    mapMarker = new google.maps.Marker({
      map: map,
      draggable: true,
      animation: google.maps.Animation.DROP,
      position: new google.maps.LatLng(
        16.750917060666666,
        -93.14536588206437
      )
    });
  }

  mapMarker.addListener('dragend', function (event) {
    const coordinates = {
      latitude: this.getPosition().lat(),
      longitude: this.getPosition().lng()
    };

    getAddress(coordinates);
  });

  // Create the search box and link it to the UI element.
  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);
  //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];
    // For each place, get the icon, name and location.
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
      markers.push(
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

      //getAddress(coordinates);
      $('#Latitud').val(coordinates.latitude);
      $('#Longitud').val(coordinates.longitude);
      $('#Direccion').val(place.formatted_address);
      $('.direccion-message').html('<p><span style="color: red; margin-top:15px;">¡IMPORTANTE! </span>Escribe la dirección de tu negocio.</p>');

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });

    console.log(markers);

    markers.length > 0 && markers.map(async function (item, index) {
      console.log(index);
      markers[index].addListener('dragend', function (event) {
        const coordinates = {
          latitude: this.getPosition().lat(),
          longitude: this.getPosition().lng()
        };

        const aaaaaaaa = this.getPosition();
        console.log(aaaaaaaa);

        getAddress(coordinates);
      });
    });

    map.fitBounds(bounds);
  });
}