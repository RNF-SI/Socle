function site_url(path) {
  // renvoie une url complete
  return base_url + 'index.php/' + path;
}

// ajoute une couche vectorielle à la carte
function addVectorLayer(map, url, options, callback) {
  $.get(site_url(url), function(data) {
    if (options === undefined) {
      var options = {
        color: 'green',
        weight: 2,
        fill: false
      }
    }
    var vectLayer = L.geoJSON(data, options).addTo(map)
    if (callback) {
      callback(vectLayer);
    }
  });
}


// crée une carte de base avec les couches qu'il faut
function base_map(id_map, id_ep) {
  var mainMap = L.map(id_map);

  var baseLayers = {};

  var ignLayer = L.tileLayer("http://wxs.ign.fr/qi0jtcvtmn01lkt0621p5yci/geoportail/wmts?" +
        "REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0" +
        "&STYLE=normal" +
        "&TILEMATRIXSET=PM" +
        "&FORMAT=image/jpeg"+
        "&LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS"+
	       "&TILEMATRIX={z}" +
        "&TILEROW={y}" +
        "&TILECOL={x}", {
      attribution: 'IGN-F/Geoportail',
      maxZoom: 18,
      tileSize: 256
  }).addTo(mainMap);
  baseLayers['IGN'] = ignLayer;

  var wmsGeolLayer = L.tileLayer.wms("http://geoservices.brgm.fr/geologie", {
    layers: "GEOLOGIE",
    format: "image/jpeg",
    attribution: "&copy; BRGM",
    maxZoom: 15
  }).addTo(mainMap);
  baseLayers["Carte géologique"] = wmsGeolLayer;

  L.control.layers(baseLayers).addTo(mainMap);
  L.control.scale({imperial: false}).addTo(mainMap);

  // contours de l'espace
  if (id_ep !== undefined) {
      var options = {
        color: 'green',
        weight: 2,
        fill: false
      }
      addVectorLayer(mainMap, 'carto/espace_protege_geom/' + id_ep, options, function(lyr) {
        lyr.eachLayer(function(slyr) {
          mainMap.monosite = slyr.feature.properties.monosite;
        });
        mainMap.fitBounds(lyr.getBounds());
      });
  }

  // traitement de la mini carte agrandissable
  if ($("#" + id_map).hasClass("minimap")) {
    var mapcont = $("#" + id_map);
    var mapParent = mapcont.parent();
    var mapheight;

    var reduceMap = function(map, btn) {
      var mapCenter = map.getCenter();
      mapcont.css({height: mapheight});
      mapParent.append(mapcont);
      map.panTo(mapCenter);
      map.invalidateSize();
      btn.state('magnify');
    };

    var reduceButton = L.easyButton({
      states: [{
        stateName: 'magnify',
        icon: 'glyphicon-fullscreen',
        title: 'aggrandir la carte',
        onClick: function(btn, map) {
          mapheight =  mapcont.height();
          var mapCenter = map.getCenter();
          $("#carto-full .modal-body").append(mapcont);
          $("#carto-full").modal("show");
          mapcont.css({height: '80vh', 'min-height': '300px'});
          map.panTo(mapCenter);
          map.invalidateSize();
          btn.state('minify')
        }
      }, {
        stateName: 'minify',
        icon: 'glyphicon-resize-small',
        title: 'réduire',
        onClick: function(btn, map) {
          $("#carto-full").modal("hide");
        }
      }]
    }).addTo(mainMap);

    $('#carto-full.modal').on('hide.bs.modal', function() {
      reduceMap(mainMap, reduceButton);
    });
  }

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
