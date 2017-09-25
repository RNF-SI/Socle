$(function() {
  $('#echelle_geol').bonsai({createInputs: 'radio'});

  // CARTO
  var map = base_map('map', ep.code_national_ep);
  var coords = $("input[name=coords]").val();
  var latlng = [0,0];
  if (coords)
    latlng = coords.split(',').reverse();
  var marker = L.marker(latlng).addTo(map);


  map.on('click', function(evt) {
    marker.setLatLng(evt.latlng);

    $("input[name=coords]").val(evt.latlng.lng + ',' + evt.latlng.lat);
    getGeolInfo(map, evt, function(response) {
      $('input#code_eg').val(response.notation);
      $('input#intitule_eg').val(response.description);
    });
  });

});
