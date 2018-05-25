$(function() {
  var map = base_map('map', null, "IGN");
  map.setView([46, 2], 8);
  $.get(site_url('carto/espaces_all'), function(data) {
    /* utilise le plugin deflate pour montrer des points à la place de polygones
     quand on dézoome */
    var deflated = L.deflate({minSize: 20});
    deflated.addTo(map);
    var eplyr = L.geoJSON(data).bindTooltip(function(lyr) {
      return lyr.feature.properties.nom;
    }).addTo(deflated);
    map.fitBounds(eplyr.getBounds());
  });
})
