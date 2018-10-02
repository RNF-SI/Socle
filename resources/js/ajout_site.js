$(function() {
  var map = base_map('map', id_ep);
  if (geom_editable) {
    map.pm.addControls({
      drawRectangle: true,
      drawPolygon: true,
      drawMarker: false,
      drawPolyline: false,
      drawCircle: false,
      cutPolygon: false
    });
  }

  var updateGeomTxt = function(lyr) {
    $('#geom').text(JSON.stringify(lyr.toGeoJSON()));
  }

  map.on('pm:create', function(e) {
    var lyr = e.layer;
    updateGeomTxt(lyr);
    lyr.on('pm:edit', function(e) {
      updateGeomTxt(lyr);
    });
  });
});
