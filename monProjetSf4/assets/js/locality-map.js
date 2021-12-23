setTimeout(function() {
    let map = L.map('mapLimites')
        .setView([29.393176, -9.546011], 12);
    let IconMarkerMap = L.divIcon({
        html: '<i class="fas fa-map-marker-alt fa-2x" style="color:#4b4f56;"></i>',
        className: 'my-div-icon',
        iconAnchor: [9, 24]
    });
    var max_fields = 100;
    var wrapper = $(".container-limites");
    var previsualisation = $("#previsualisationMap");
    var add_coordinates = $(".add_form_field");
    var html_bloc_inputs_coordinates = '<div class="coupleLimites input-group" style="border: 1px dashed #cecece;"><input type="text" placeholder="Altitude" value="VALEUR_ALT" name="alt"/> <input type="text" placeholder="Longitude" value="VALEUR_LON" name="lon"/><a href="#" class="delete"> <i class="fas fa-times fa-3x"></i> </a> <a href="#"> <i class="fas fa-street-view show-marker fa-3x" style="margin-left:1em;"></i></a></div>';
    var x = 1;

    $(add_coordinates).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            var input = html_bloc_inputs_coordinates.replace("VALEUR_ALT", "");
            $(wrapper).append(input.replace("VALEUR_LON", ""));
        } else {
            alert('Aucune limite pour cet élément détectée.');
        }
    });
    if (edit) {
        let coordinates = input_coordinates.val();
        var el = hydratFields();
        coordinates ? placeLimitsOnMap(JSON.parse(coordinates)) : '';
        console.log(coordinates);
        if (coordinates) {
            for (var i = 0; i < JSON.parse(coordinates).length; i++) {
                var input = html_bloc_inputs_coordinates.replace("VALEUR_ALT", JSON.parse(coordinates)[i][0]);
                $(wrapper).append(input.replace("VALEUR_LON", JSON.parse(coordinates)[i][1]));
                L.marker([JSON.parse(coordinates)[i][0], JSON.parse(coordinates)[i][1]], { icon: el['IconType'] }).addTo(map).bindPopup(el['typeLocality']);
            }
        }
    } else {
        console.log('youpi');
        placeLimitsOnMap(null);
    }
    hydratFields();

    $(wrapper).on("click", ".delete", function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });
    $(previsualisation).click(function(e) {
        e.preventDefault();
        hydratFields();
    });

    var osm2 = new L.TileLayer(getOsmMap(), { minZoom: 0, maxZoom: 18 });
    var miniMap = new L.Control.MiniMap(osm2, { toggleDisplay: true, minZoom: 3, maxZoom: 8, zoomLevelFixed: 3 }).addTo(map);
    $('.leaflet-control-minimap').append('<div class="title-country-map">Morocco ⵍⵎⵖⵔⵉⴱ المغرب</div>');
    $('.leaflet-control-minimap').append('<div class="position-overlay-pan"></div>');

    function placeLimitsOnMap(limites) {
        $.when(limites).done(function() {
            clearMap(map);
            L.tileLayer(getOsmMap(), {
                dragging: !L.Browser.mobile
            }).addTo(map);
            if (isPolygon) {
                L.polygon([
                    limites
                ]).addTo(map);
            }
        });
    }

    function getOsmMap() {
        return 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
    }

    function clearMap(map) {
        for (i in map._layers) {
            if (map._layers[i]._path !== undefined) {
                try {
                    map.removeLayer(map._layers[i]);
                } catch (e) {
                    console.log("probleme avec " + e + map._layers[i]);
                }
            }
        }
    }

    function hydratFields() {
        var picto = $('input#locality_map_picto').val();
        var color = $('input#locality_map_color').val();
        var typeLocality = $('input#locality_map_localityType').val();
        let IconType = L.divIcon({
            html: '<i class="' + picto + '" style="color:' + color + ';"></i>'
        });
        var tabLimites = new Array();
        $('.coupleLimites').each(function(index) {
            let alt = $(this).find('input[name="alt"]').val();
            let lon = $(this).find('input[name="lon"]').val();
            var xandy = new Array(alt, lon);
            L.marker([alt, lon], { icon: IconType }).addTo(map).bindPopup(typeLocality).closePopup();
            tabLimites[index] = xandy;
        });
        input_coordinates.val(tabLimites);
        placeLimitsOnMap(tabLimites);

        return { 'picto': picto, 'color': color, 'typeLocality': typeLocality, 'IconType': IconType };
    }
    var marker = L.marker([0, 0]);
    $(wrapper).on("click", ".show-marker", function(e) {
        e.preventDefault();
        $('.coupleLimites').each(function() {
            $(this).css('background', '');
        });

        map.removeLayer(marker);
        $($(this).closest('div')).css('background', 'gold');
        var x = $($(this).closest('div').children('input')[0]).val();
        var y = $($(this).closest('div').children('input')[1]).val();
        marker = L.marker([x, y], { icon: IconMarkerMap }).addTo(map);
    });

}, 3000);