$(function() {
  var bm = new BaseMap('map', {currentBaseLayer: "IGN topo"});
  bm.map.setView([46, 2], 8);
  $.get(site_url('carto/espaces_all'), function(data) {
    /* utilise le plugin deflate pour montrer des points à la place de polygones
     quand on dézoome */
    var deflated = L.deflate({minSize: 20});
    deflated.addTo(bm.map);
    var eplyr = L.geoJSON(data, {onEachFeature: function(ft, lyr) {
      lyr.bindTooltip(function() {
        return lyr.feature.properties.nom;
      });
      lyr.on('click', function() {
        var url = site_url('espace/fiche_espace/' + ft.properties.id);
        window.location.href = url;
      })
    }}).addTo(deflated);
    bm.map.fitBounds(eplyr.getBounds());
  });
})
