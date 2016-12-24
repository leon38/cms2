/**
 * Created by DCA on 01/12/2016.
 */
var map;
var geocoder;

var gMapsLoaded = false;
window.gMapsCallback = function(){
    gMapsLoaded = true;
    $(window).trigger('gMapsLoaded');
};

window.loadGoogleMaps = function(){
    if(gMapsLoaded) return window.gMapsCallback();
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type","text/javascript");
    script_tag.setAttribute("src","http://maps.google.com/maps/api/js?sensor=false&callback=gMapsCallback");
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
};

function initMap() {
    map = new google.maps.Map(document.getElementById('map_itineraires'), {
        center: {lat: points[0].latitude, lng: points[0].longitude},
        zoom: 8,
        styles: [{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#1e242b"},{"lightness":"5"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#1e242b"},{"saturation":"0"},{"lightness":"30"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"color":"#1e242b"},{"lightness":"30"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#1e242b"},{"lightness":"20"},{"weight":"1.00"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":"-20"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"lightness":"-20"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#1e242b"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"color":"#1e242b"},{"lightness":"30"}]},{"featureType":"landscape","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#1e242b"},{"lightness":"5"}]},{"featureType":"poi","elementType":"labels","stylers":[{"color":"#1e242b"},{"lightness":"30"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#1e242b"},{"lightness":"15"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#1e242b"},{"lightness":"6"}]},{"featureType":"transit","elementType":"labels","stylers":[{"color":"#1e242b"},{"lightness":"30"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#010306"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]}]
    });

    for (var i=0; i < points.length; i++) {
        var latLng = new google.maps.LatLng(points[i].latitude, points[i].longitude);
        var marker = new google.maps.Marker({
            map: map,
            position: latLng,
            icon: {
                url: 'data:image/svg+xml;utf-8, \
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" version="1.1"> \
                    <path d="M0 0" /> \
                    <circle cx="10" cy="10" r="3" fill="red" fill-opacity="1" /> \
                    <circle cx="10" cy="10" r="10" fill="red" fill-opacity=".3" /> \
                </svg>',
                anchor: new google.maps.Point(16, 16)
            }
        });
        marker.setMap(map);
    }
}

$(document).ready(function() {
    $(window).bind('gMapsLoaded', initMap);
    window.loadGoogleMaps();
});