$(function() {
  var bm = new BaseMap('map', {id_ep: id_ep});
  var lyr = L.geoJSON(point_coords, {pointToLayer: function(jsonPt, latlng) {
    return L.circleMarker(latlng, {radius: 7, color: '#b78a31', fillColor: '#b78a31', fillOpacity: 0.5})
  }}).addTo(bm.map);

  $.get(site_url('carto/affleurements_by_eg/'+entite_id), function(data) {
    var lyr = L.geoJSON(data).addTo(bm.map);
  });

  $('.suppression-eg').click(function() {
    if (confirm("Voulez-vous vraiment supprimer cette entité et tous les éléments associés ?")) {
      window.location.href = site_url('site/suppr_entite_geol/' + entite_id);
    }
    return false;
  });

  $('.suppression-affleurement').click(function() {
    if (confirm("Voulez-vous vraiment supprimer cet affleurement ?")) {
      window.location.href = site_url('site/suppr_affleurement/' + affleurement_id);
    }
    return false;
  });
});
