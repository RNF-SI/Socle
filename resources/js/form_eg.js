$(function() {
  var $echelle = $('#echelle_geol');
  $echelle.bonsai({createInputs: 'radio'});
  var bonsai = $echelle.data('bonsai');
  $('#echelle_geol li').click(function(e) {
    bonsai.expand($(this));
  })

  // CARTO
  var bm = new BaseMap('map', {id_ep: site.ep_id, displayPopup: false});

  var lyr = L.geoJSON(undefined, {pointToLayer: function(jsonPt, latlng) {
    return L.circleMarker(latlng, {radius: 7, color: '#b78a31', fillColor: '#b78a31', fillOpacity: 0.5})
  }}).addTo(bm.map);

  var geojsonstr = $("input[name=geojson]").val();
  if (geojsonstr) {
    var geojson = JSON.parse(geojsonstr);
    lyr.addData(geojson);
  }

  bm.map.on('click', function(evt) {
    // auto-remplissage des champs par clic sur la carte
    var json = lyr.toGeoJSON();
    if (json.features.length == 0) {
      json.features.push({geometry: {coordinates: [], type: "MultiPoint"}, type: "Feature"});
    }
    json.features[0].geometry.coordinates.push([evt.latlng.lng, evt.latlng.lat]);
    lyr.clearLayers();
    lyr.addData(json);

    $("input[name=geom]").val(JSON.stringify(json.features[0].geometry));

    bm.getGeolInfo(evt, function(response) {
      if ('type' in response && response.type == 'FeatureCollection' && response.features.length > 0) {
        response = response.features[0].properties;
      }
      $('input#code').val(response.notation);
      if ($('input#intitule').val() == "") {
        $('input#intitule').val(response.description);
      }
      $('input#nom_carte').val(response.description);
    });
  });

  L.easyButton('fa-times', function(btn, map) {
    lyr.clearLayers();
    $('input[name=geom]').val(undefined);
  }).addTo(bm.map);

});
