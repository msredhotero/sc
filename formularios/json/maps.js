function initMap() {
//google.maps.event.addDomListener(window, "load", function() {

    //const ubicacion = new Localizacion(() => {
        /*
        const myLatLng = {lat: -34.5893799, lng: -58.3855431};

        var texto = '<h1> Nombre del lugar </h1>' + '<p> Descripcion del lugar </p>' + '<a href="https://wwww.google.com">Pagina Web</a>';

        const options = {
            center: myLatLng,
            zoom:14
        }

        var map = document.getElementById('map');

        const mapa = new google.maps.Map(map, options);

        const marcador = new google.maps.Marker({
            position: myLatLng,
            map: mapa,
            title: 'mi primer marcado'
        });

        var informacion = new google.maps.InfoWindow({
            content: texto
        });

        marcador.addListener('click', function() {
            informacion.open(mapa, marcador);
        });

        var autocomplete = document.getElementById('autocomplete');

        const busqueda = new google.maps.places.Autocomplete(autocomplete);
        busqueda.bindTo('bounds',mapa);
        */
    //});

    var markers = [];

    const center = { lat: -34.5893799, lng: -58.3855431 };

    

    // The map, centered at center
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 14,
        center: center
    });

    

    // The marker, positioned at center
    const marker = new google.maps.Marker({
        position: center,
        map: map,
    });

    map.addListener('click', function(e) {
			
        if (markers.length > 0) {
            clearMarkers();
        }
        $('#latitud').val(e.latLng.lat());
        $('#longitud').val(e.latLng.lng());	
        placeMarkerAndPanTo(e.latLng, map);
    });

    function placeMarkerAndPanTo(latLng, map) {
        var marker = new google.maps.Marker({
            position: latLng,
            map: map
        });
        markers.push(marker);
        map.panTo(latLng);
        
    }

    function clearMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        
    }

    
}