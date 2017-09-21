$(function() {
  var map = base_map('map', id_ep_ref);
  var coords = point_coords.split(',').reverse();
  var marker = L.marker(coords).addTo(map);
});
