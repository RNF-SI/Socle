function site_url(path) {
  // renvoie une url complete
  return base_url + 'index.php/' + path;
}


// crée une carte de base avec les couches qu'il faut
function base_map(id_map, id_ep_ref) {
  var mainMap = L.map(id_map).setView([46, 1], 7);
  var osmLayer = L.tileLayer('http://tile-{s}.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
      maxZoom: 18
  }).addTo(mainMap);
  var baseLayers = {"OpenstreetMap": osmLayer};

  // contours de la réserve
  $.get(site_url("carto/espace_protege_geom/" + id_ep_ref), function(data) {
    var wmsGeolLayer = L.tileLayer.wms("http://geoservices.brgm.fr/geologie", {
      layers: "GEOLOGIE",
      format: "image/png",
      attribution: "&copy; BRGM"
    }).addTo(mainMap);
    baseLayers["Carte géologique"] = wmsGeolLayer;
    L.control.layers(baseLayers).addTo(mainMap);

    var vectLayer = L.geoJSON(data).addTo(mainMap);
    mainMap.fitBounds(vectLayer.getBounds());
  });

  return mainMap;
}
