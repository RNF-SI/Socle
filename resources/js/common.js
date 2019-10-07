function site_url(path) {
  // renvoie une url complete
  return base_url + 'index.php/' + path;
}

// Objet à instancier pour une carte standardisée
function BaseMap (id, options) {
  this.ignKey = "qi0jtcvtmn01lkt0621p5yci";
  this.options = {
    monosite: true,
    displayPopup: true,
    reductible: false,
    currentBaseLayer: 'carte géologique',
  };
  if (options) {
    this.options = Object.assign(this.options, options);
  }

  this.baseLayers = {
    'Photos aériennes': L.tileLayer("http://wxs.ign.fr/" + this.ignKey + "/wmts?" +
        "REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0" +
        "&STYLE=normal" +
        "&TILEMATRIXSET=PM" +
        "&FORMAT=image/jpeg"+
        "&LAYER=ORTHOIMAGERY.ORTHOPHOTOS" +
	       "&TILEMATRIX={z}" +
        "&TILEROW={y}" +
        "&TILECOL={x}", {
      attribution: 'IGN-F/Geoportail',
      maxZoom: 18,
      tileSize: 256
    }),
    'IGN topo': L.tileLayer("http://wxs.ign.fr/" + this.ignKey + "/wmts?" +
        "REQUEST=GetTile&SERVICE=WMTS&VERSION=1.0.0" +
        "&STYLE=normal" +
        "&TILEMATRIXSET=PM" +
        "&FORMAT=image/jpeg"+
        "&LAYER=GEOGRAPHICALGRIDSYSTEMS.MAPS.SCAN-EXPRESS.STANDARD"+
        "&TILEMATRIX={z}" +
        "&TILEROW={y}" +
        "&TILECOL={x}", {
      attribution: 'IGN-F/Geoportail',
      maxZoom: 18,
      tileSize: 256
    }),
    'carte géologique': L.tileLayer.wms("http://geoservices.brgm.fr/geologie", {
        layers: "GEOLOGIE",
        format: "image/jpeg",
        attribution: "&copy; BRGM",
        maxZoom: 15
      })
  },

  this.popup = L.popup({maxWidth: 200}),

  this.id = id;

  this.map = L.map(this.id);

  this.addVectorLayer = function(url, options, callback) {
    var this1 = this;
    $.get(site_url(url), function(data) {
      if (options === undefined) {
        options = {
          color: 'green',
          weight: 2,
          fill: false
        }
      }
      var vectLayer = L.geoJSON(data, options).addTo(this1.map)
      if (callback) {
        callback(vectLayer);
      }
    })
  };

  // téléchargement des infos BRGM sur un point cliqué
  this.getGeolInfo = function(evt, callback) {
    var url = site_url('carto/featureInfoProxy');
    var size = this.map.getSize();
    var params = {
      BBOX: this.map.getBounds().toBBoxString(),
      WIDTH: size.x,
      HEIGHT: size.y,
      X: Math.round(evt.containerPoint.x),
      Y: Math.round(evt.containerPoint.y)
    };
    $.get(url, params, callback);
  };

  this.enablePopup = function(enabled) {
    this.options.displayPopup = enabled;
  };

  L.control.scale({imperial: false}).addTo(this.map);

  this.baseLayers[this.options.currentBaseLayer].addTo(this.map);
  L.control.layers(this.baseLayers).addTo(this.map);

  if (this.options.id_ep) {
    var options = {
      color: 'green',
      weight: 2,
      fill: false,
      pmIgnore: true
    }
    var this1 = this;
    this.addVectorLayer('carto/espace_protege_geom/' + this.options.id_ep, options, function(lyr) {
      lyr.eachLayer(function(slyr) {
        this1.options.monosite = slyr.feature.properties.monosite;
      });
      this1.map.fitBounds(lyr.getBounds());
    });
  } else {
    this.map.setView([47, 2], 8);
  }

  // popup infos géol
  var this1 = this;
  this.map.on("click", function(evt) {
    if (! this1.options.displayPopup)
      return;

    if (this1.map.getZoom() < 11) return false;
    this1.popup.setLatLng(evt.latlng);
    this1.getGeolInfo(evt, function(data) {
      var cont = '<p><b>Entité géologique :</b><br />' + data.notation + ' : <i>'
        + data.description + '</i></p><p><a href="http://ficheinfoterre.brgm.fr/Notices/'
        + ("0000" + data.carte).slice(-4)
        + 'N.pdf" target="_blank">consulter la notice</a></p>';
      this1.popup.setContent(cont).openOn(this1.map);
    });
  });

  // traitement de la mini carte agrandissable
  if (this.options.reductible) {
    var mapcont = $("#" + this.id);
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
        icon: 'fa-expand-arrows-alt',
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
        icon: 'fa-window-minimize',
        title: 'réduire',
        onClick: function(btn, map) {
          $("#carto-full").modal("hide");
        }
      }]
    }).addTo(this.map);

    var map = this.map;
    $('#carto-full.modal').on('hide.bs.modal', function() {
      reduceMap(map, reduceButton);
    });
  }

  // Creates a polygon from a circle
  this.createGeodesicPolygon = function(circle) {
    var destinationVincenty = function(lonlat, brng, dist) { //rewritten to work with leaflet
      var a = 6378137, b = 6356752.3142, f = 1/298.257223563;
      var lon1 = lonlat.lng;
      var lat1 = lonlat.lat;
      var s = dist;
      var pi = Math.PI;
      var alpha1 = brng * pi/180 ; //converts brng degrees to radius
      var sinAlpha1 = Math.sin(alpha1);
      var cosAlpha1 = Math.cos(alpha1);
      var tanU1 = (1-f) * Math.tan( lat1 * pi/180 /* converts lat1 degrees to radius */ );
      var cosU1 = 1 / Math.sqrt((1 + tanU1*tanU1)), sinU1 = tanU1*cosU1;
      var sigma1 = Math.atan2(tanU1, cosAlpha1);
      var sinAlpha = cosU1 * sinAlpha1;
      var cosSqAlpha = 1 - sinAlpha*sinAlpha;
      var uSq = cosSqAlpha * (a*a - b*b) / (b*b);
      var A = 1 + uSq/16384*(4096+uSq*(-768+uSq*(320-175*uSq)));
      var B = uSq/1024 * (256+uSq*(-128+uSq*(74-47*uSq)));
      var sigma = s / (b*A), sigmaP = 2*Math.PI;
      while (Math.abs(sigma-sigmaP) > 1e-12) {
          var cos2SigmaM = Math.cos(2*sigma1 + sigma);
          var sinSigma = Math.sin(sigma);
          var cosSigma = Math.cos(sigma);
          var deltaSigma = B*sinSigma*(cos2SigmaM+B/4*(cosSigma*(-1+2*cos2SigmaM*cos2SigmaM)-
              B/6*cos2SigmaM*(-3+4*sinSigma*sinSigma)*(-3+4*cos2SigmaM*cos2SigmaM)));
          sigmaP = sigma;
          sigma = s / (b*A) + deltaSigma;
      }
      var tmp = sinU1*sinSigma - cosU1*cosSigma*cosAlpha1;
      var lat2 = Math.atan2(sinU1*cosSigma + cosU1*sinSigma*cosAlpha1,
          (1-f)*Math.sqrt(sinAlpha*sinAlpha + tmp*tmp));
      var lambda = Math.atan2(sinSigma*sinAlpha1, cosU1*cosSigma - sinU1*sinSigma*cosAlpha1);
      var C = f/16*cosSqAlpha*(4+f*(4-3*cosSqAlpha));
      var lam = lambda - (1-C) * f * sinAlpha *
          (sigma + C*sinSigma*(cos2SigmaM+C*cosSigma*(-1+2*cos2SigmaM*cos2SigmaM)));
      var lamFunc = lon1 + (lam * 180/pi); //converts lam radius to degrees
      var lat2a = lat2 * 180/pi; //converts lat2a radius to degrees

      return L.latLng(lamFunc, lat2a);
    };

    var sides = 20;
    var latlon = circle.getLatLng(), radius = circle.getRadius();
    var points = [];

    for (var i = 0; i < sides; i++) {
      var angle = (i * 360 / sides);
      var geom_point = destinationVincenty(latlon, angle, radius);

      points.push(geom_point);
    }

    return L.polygon(points);
  };

};


function activate_popover(parent) {
  $(parent).find(".description-tooltip").popover({
    title: "Définition",
    html: true,
    trigger: 'manual',
    animation: false
  }).on('mouseenter', function () {
    var _this = this;
    $(this).popover('show');
    $('.popover').on('mouseleave', function () {
        $(_this).popover('hide');
    });
  }).on('mouseleave', function () {
      var _this = this;
      setTimeout(function () {
          if (!$('.popover:hover').length) {
              $(_this).popover('hide');
          }
      }, 300);
  });
}


$(function() {
  // tooltip
  activate_popover("body");

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
