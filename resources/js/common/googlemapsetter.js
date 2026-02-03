import { Loader } from "@googlemaps/js-api-loader";

let lat = null;
let lng = null;
let zoom = null;

const loader = new Loader({
  apiKey: "you_api_key_from_env",
  version: "weekly",
  libraries: ["marker"],
});

loader.importLibrary("maps").then(() => {
  const map = new google.maps.Map(document.getElementById("modal-map"), {
    center: {
      lat: 13.4125,
      lng: 103.867,
    },
    zoom: 8,
  });

  let infoWindow = new google.maps.InfoWindow({
    content: "Add this Lat/Lng to property value",
    position: {
      lat: 13.4125,
      lng: 103.867,
    },
  });
  infoWindow.open(map);
  map.addListener("click", function (mapsMouseEvent) {
    infoWindow.close();
    lat = mapsMouseEvent.latLng.lat();
    lng = mapsMouseEvent.latLng.lng();
    zoom = map.getZoom();
    infoWindow.setPosition({
      lat: lat,
      lng: lng,
    });
    infoWindow.setContent("lat = " + lat + "<br/><br/>" + " lng = " + lng);
    infoWindow.open(map);
  });
});

$(document).on("click", "#lat-lng-add", function () {
  console.log("hey");
  $("#real-lat").val(lat);
  $("#real-lng").val(lng);
  $("#zoom").val(zoom);
  $("#gps").val(convertDMS(lat, lng));
  // $("#map-modal-box").hide
});

//@docs https://stackoverflow.com/questions/37893131/how-to-convert-lat-long-from-decimal-degrees-to-dms-format
/**
 * For lat = 11.5734436 
  11.5734436 => 11º34'24.397" N
  degrees = 11
  minutesNotation = (0.5734436) * 60 = 34.406616
  minutes = 34
  seconds = 0.406616 * 60 = 24.397s
 */
function toDegreesMinutesAndSeconds(coordinate) {
  var absolute = Math.abs(coordinate);
  var degrees = Math.floor(absolute);
  var minutesNotTruncated = (absolute - degrees) * 60;
  var minutes = Math.floor(minutesNotTruncated);
  var seconds = ((minutesNotTruncated - minutes) * 60).toFixed(2);

  return degrees + "°" + minutes + "'" + seconds + '"';
}

function convertDMS(lat, lng) {
  var latitude = toDegreesMinutesAndSeconds(lat);
  var latitudeCardinal = lat >= 0 ? "N" : "S";

  var longitude = toDegreesMinutesAndSeconds(lng);
  var longitudeCardinal = lng >= 0 ? "E" : "W";

  return latitude + latitudeCardinal + " " + longitude + longitudeCardinal;
}
