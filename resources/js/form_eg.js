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
    var url = site_url('carto/featureInfoProxy');
    var size = map.getSize();
    var params = {
      BBOX: map.getBounds().toBBoxString(),
      WIDTH: size.x,
      HEIGHT: size.y,
      X: evt.containerPoint.x,
      Y: evt.containerPoint.y
    };

    $("input[name=coords]").val(evt.latlng.lng + ',' + evt.latlng.lat);
    $.get(url, params, function(response) {
      $('input#code_eg').val(response.notation);
      $('input#intitule_eg').val(response.description);
    });
  });

});
