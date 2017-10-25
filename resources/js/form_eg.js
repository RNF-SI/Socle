$(function() {
  $('#echelle_geol').bonsai({createInputs: 'radio'});

  // CARTO
  var map = base_map('map', ep.code_national_ep);

  var lyr = L.geoJSON(undefined, {pointToLayer: function(jsonPt, latlng) {
    return L.circleMarker(latlng, {radius: 7, color: '#b78a31', fillColor: '#b78a31', fillOpacity: 0.5})
  }}).addTo(map);

  var geojsonstr = $("input[name=geojson]").val();
  if (geojsonstr) {
    var geojson = JSON.parse(geojsonstr);
    lyr.addData(geojson);
  }

  map.on('click', function(evt) {
    var json = lyr.toGeoJSON();
    if (json.features.length == 0) {
      json.features.push({geometry: {coordinates: [], type: "MultiPoint"}, type: "Feature"});
    }
    json.features[0].geometry.coordinates.push([evt.latlng.lng, evt.latlng.lat]);
    lyr.clearLayers();
    lyr.addData(json);

    $("input[name=geojson]").val(JSON.stringify(json.features[0].geometry));

    getGeolInfo(map, evt, function(response) {
      $('input#code_eg').val(response.notation);
      $('input#intitule_eg').val(response.description);
    });
  });

  L.easyButton('glyphicon-remove', function(btn, map) {
    lyr.clearLayers();
    $('input[name=geojson]').val(undefined);
  }).addTo(map);

});
