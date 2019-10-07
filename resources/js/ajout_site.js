$(function() {
  var bm = new BaseMap('map', {id_ep: id_ep});
  if (geom_editable) {
    bm.map.pm.addControls({
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

  bm.map.on('pm:create', function(e) {
    var lyr = e.layer;
    updateGeomTxt(lyr);
    lyr.on('pm:edit', function(e) {
      updateGeomTxt(lyr);
    });
  });
});
