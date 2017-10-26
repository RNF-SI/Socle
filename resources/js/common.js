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

  var wmsGeolLayer = L.tileLayer.wms("http://geoservices.brgm.fr/geologie", {
    layers: "GEOLOGIE",
    format: "image/png",
    attribution: "&copy; BRGM"
  }).addTo(mainMap);
  baseLayers["Carte géologique"] = wmsGeolLayer;

  L.control.layers(baseLayers).addTo(mainMap);
  L.control.scale({imperial: false}).addTo(mainMap);

  // contours de la réserve
  $.get(site_url("carto/espace_protege_geom/" + id_ep_ref), function(data) {
    var vectLayer = L.geoJSON(data).addTo(mainMap).bringToBack();
    mainMap.fitBounds(vectLayer.getBounds());
  });

  return mainMap;
}


$(function() {
    // identification
    $("#login-link").click(function(e) {
      $.get(site_url('utilisateurs/login'), function (response) {
        var msgbox = $(response);
        $("#login-form-modal").remove();
        $("body").append(msgbox);
        msgbox.find("#login-form").on('submit', function(evt) {
          evt.preventDefault();
          $.post(site_url('utilisateurs/login'), $(this).serialize(), function(response) {
            if (response.success) {
              location.reload();
            } else {
              $("#login-message").addClass('alert alert-danger').html(response.message);
            }
          });
        });
        msgbox.modal("show");
      });
      return false;
    });

    // logout
    $("#logout-link").click(function() {
      $.get(site_url('utilisateurs/logout'), function() {
        location.reload();
      });
      return false;
    });
});

// téléchargement des infos BRGM sur un point cliqué
function getGeolInfo(map, evt, callback) {
  var url = site_url('carto/featureInfoProxy');
  var size = map.getSize();
  var params = {
    BBOX: map.getBounds().toBBoxString(),
    WIDTH: size.x,
    HEIGHT: size.y,
    X: Math.round(evt.containerPoint.x),
    Y: Math.round(evt.containerPoint.y)
  };
  $.get(url, params, callback);
}
