$(function() {
  var map = base_map('map');
  addVectorLayer(map, 'carto/site_geom/' + site_id, null, function(lyr) {
    map.fitBounds(lyr.getBounds());
  });
})
