$(function() {
  var bm = new BaseMap('map');
  $.get(site_url('carto/site_subelements_geom/' + site_id), function(data) {
    if (data.site.geometry == null) {
      $('#map').remove();
      return;
    }
    var siteLayer = L.geoJSON(data.site, {style: function(ft) {
      return {color: 'blue'};
    }});
    var egLayer = L.geoJSON(data.egs, {pointToLayer: function(pt, latlng) {
      return L.circleMarker(latlng, {radius: 7, color: '#b78a31', fillColor: '#b78a31', fillOpacity: 0.5});
    }});
    var afflLayer = L.geoJSON(data.egs, {pointToLayer: function(pt, latlng) {
      return L.circleMarker(latlng, {radius: 5, color: '#3f51b5', fillColor: '#3f51b5', fillOpacity: 0.5});
    }});
    bm.map.addLayer(siteLayer);
    bm.map.fitBounds(siteLayer.getBounds());
    bm.map.addLayer(egLayer);
    bm.map.addLayer(afflLayer);
  });

  $('#map').css({width: '100%'});
})
