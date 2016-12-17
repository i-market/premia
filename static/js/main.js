window.App = {
    googleMapsCallback: function() {
        var markerLatLng = { lat: 55.470993, lng: 37.712681 };
        var map = new google.maps.Map($('#contacts-map')[0], {
            zoom: 13,
            center: markerLatLng
        });
        new google.maps.Marker({
            map: map,
            position: markerLatLng
        });
    }
};