$(function() {
  var bm = new BaseMap('map');
  var ptlyr = L.circleMarker(undefined, {radius: 7, color: '#b78a31', fillColor: '#b78a31', fillOpacity: 0.5});
  bm.addVectorLayer('carto/site_geom/' + site_id, null, function(lyr) {
    var geojsonstr = $("input[name=geom]").val();
    if (geojsonstr) {
      var geojson = JSON.parse(geojsonstr.replace(/&quot;/g, '"'));
      ptlyr.setLatLng(geojson.coordinates.reverse());
    } else {
      ptlyr.setLatLng(lyr.getBounds().getCenter());
    }
    ptlyr.addTo(bm.map);
  }, true);

  bm.map.on('click', function(evt) {
    ptlyr.setLatLng(evt.latlng);
    var json = ptlyr.toGeoJSON();
    $("input[name=geom]").val(JSON.stringify(json.geometry));
  });

})
