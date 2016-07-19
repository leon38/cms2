/**
 * Created by DCA on 13/07/2016.
 */
var map;
var geocoder;

function initMap() {
    geocoder = new google.maps.Geocoder();
    map = new google.maps.Map(document.getElementsByClassName('map')[0], {
        center: {lat: -34.397, lng: 150.644},
        zoom: 8
    });
    infowindow = new google.maps.InfoWindow;
    codeAddress();
}

function codeAddress() {
    var address = document.getElementById("tc_bundle_contentbundle_content_fieldValuesTemp_carte_address").value;
    var latitude = document.getElementById('tc_bundle_contentbundle_content_fieldValuesTemp_carte_latitude').value;
    var longitude = document.getElementById('tc_bundle_contentbundle_content_fieldValuesTemp_carte_longitude').value;

    if (address != "" && latitude == "" && longitude == "") {
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                document.getElementById('tc_bundle_contentbundle_content_fieldValuesTemp_carte_latitude').value = results[0].geometry.location.lat();
                document.getElementById('tc_bundle_contentbundle_content_fieldValuesTemp_carte_longitude').value = results[0].geometry.location.lng();
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    animation: google.maps.Animation.DROP
                });
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    } else {
        geocodeLatLng(geocoder, map)
    }
}

function geocodeLatLng(geocoder, map) {
    var latitude = document.getElementById('tc_bundle_contentbundle_content_fieldValuesTemp_carte_latitude').value;
    var longitude = document.getElementById('tc_bundle_contentbundle_content_fieldValuesTemp_carte_longitude').value;
    var latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};
    console.log(latlng);
    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                map.setZoom(11);
                new google.maps.Marker({
                    position: latlng,
                    map: map,
                    animation: google.maps.Animation.DROP
                });
                map.setCenter(latlng);
            } else {
                window.alert('No results found');
            }
        } else {
            window.alert('Geocoder failed due to: ' + status);
        }
    });
}