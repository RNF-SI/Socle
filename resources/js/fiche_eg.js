$(function() {
  var map = base_map('map', id_ep);
  var lyr = L.geoJSON(point_coords, {pointToLayer: function(jsonPt, latlng) {
    return L.circleMarker(latlng, {radius: 7, color: '#b78a31', fillColor: '#b78a31', fillOpacity: 0.5})
  }}).addTo(map);
  $.get(site_url('carto/affleurements_by_eg/'+entite_id), function(data) {
    var lyr = L.geoJSON(data).addTo(map);
  })
});