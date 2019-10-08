$(function() {
  var bm = new BaseMap('map');

  var siteStyle = {color: 'blue'};
  var egColor = '#CC6600';
  var egPointStyle = {radius: 7, color: egColor, fillColor: egColor, fillOpacity: 0.5};
  var egPathStyle = {color: egColor, fillColor: egColor, fillOpacity: 0.2};
  var afflStyle = {radius: 5, color: '#3f51b5', fillColor: '#3f51b5', fillOpacity: 0.5};
  var itemStyle = {color: '#b78a31', fillColor: '#b78a31', fillOpacity: 0.2};

  $.get(site_url('carto/site_subelements_geom/' + site_id), function(data) {
    if (data.site.features[0].geometry == null) {
      $('#map').remove();
      return;
    }
    var siteLayer = L.geoJSON(data.site, {style: function(ft) { return siteStyle; }});

    var egLayer = L.geoJSON(data.egs, {
      pointToLayer: function(pt, latlng) {
        return L.circleMarker(latlng, egPointStyle);
      },
      style: function(ft) {return  egPathStyle},
      onEachFeature: function(ft, lyr) { lyr.bindTooltip("Entité géologique : " + ft.properties.nom) }
    });
    var afflLayer = L.geoJSON(data.affleurements, {
      pointToLayer: function(pt, latlng) {
        return L.circleMarker(latlng, afflStyle);
      },
      onEachFeature: function(ft, lyr) { lyr.bindTooltip(ft.properties.type + " : " + ft.properties.nom) }
    });
    var itemLayer = L.geoJSON(data.site_qcm, {
      style: itemStyle,
      onEachFeature: function(ft, lyr) { lyr.bindTooltip(ft.properties.label) }
    })
    itemLayer.addData(data.eg_qcm);
    bm.map.addLayer(siteLayer);
    bm.map.fitBounds(siteLayer.getBounds());
    bm.map.addLayer(egLayer);
    bm.map.addLayer(afflLayer);
    bm.map.addLayer(itemLayer);
  });

})
