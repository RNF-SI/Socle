// fonctions de chargement des composants RubriquesController

// TODO : empecher la fermeture du volet si des infos ont été saisies

function load_content(evt) {
  // contenu rubrique
  var $rubrique = $(evt.target).parents(".rubrique");
  var id_rubrique = $rubrique.attr('id');
  var $container  = $rubrique.find(".rubrique-content");
  var $messageBox = $container.siblings(".message");
  $messageBox.html("");
  $rubrique.find(".rubrique-toolbar .btn-group")
    .html('<button class="btn btn-primary button-edit-form"><span class="fas fa-edit"></span> Editer</button>');
  $.ajax(site_url("site/rubrique_content/" + entite_id + "/" + id_rubrique + '/' + type_rubrique), {
    success: function(data) {
      $container.html(data);
      activate_popover($rubrique);
    },
    error: function(xhr, status) {
      $messageBox.html('<div class="alert alert-danger">Erreur de chargement :' + status + '</div>');
    }
  });
}

// soumission du formulaire
function submit_form(evt) {
  evt.preventDefault();
  var $container = $(evt.target).parents(".rubrique").find(".rubrique-content");
  var messageBox = $container.siblings(".message");
  var $form = $container.find("form");

  var params = {
    url: $form.attr("action"),
    data: {data: JSON.stringify($form.serializeArray())},
    type: 'POST',
    success: function(response) {
        messageBox.empty();
        if (! response.success) { // echec de validation (retourne du json)
          messageBox.html('<div class="alert alert-warning">' + response.message + '</div>')
        } else {
          load_content(evt);
        }
    },
    error: function(req, status, message) {
      messageBox.html('<div class="alert alert-danger">Erreur d\'enregistrement : <br />' + status + ': ' + message);
    }
  }
  // traitement de l'upload
  if ($form.attr("enctype") == 'multipart/form-data') {
    var file_data = $container.find("input[name='photo']").prop('files')[0];
    var form_data = new FormData(this);
    form_data.append('photo', file_data);
    params.processData = false;
    params.data = form_data;
    params.contentType = false;
  }

  $.ajax(params);
  return false;
}

function load_form(evt) {
  // Affichage du formulaire
  var $rubrique = $(evt.target).parents(".rubrique").first();
  var id_rubrique = $rubrique.attr('id');
  $rubrique.find(".rubrique-toolbar .btn-group")
    .html('<button class="btn btn-primary button-save"><span class="fas fa-save"></span> Enregistrer</button>'
    + '<button class="btn btn-primary button-cancel"><span class="fas fa-times"></span> Annuler</button>');
  $.get(site_url("site/rubrique_form/" + entite_id + "/" + id_rubrique + '/' + type_rubrique), function(data) {
    var form = $(data);
    $rubrique.find(".rubrique-content").empty().append(form);
    activate_popover($rubrique);
  });
}


function destinationVincenty(lonlat, brng, dist) { //rewritten to work with leaflet
  var ct = {
      a: 6378137,
      b: 6356752.3142,
      f: 1/298.257223563
  };
  var a = ct.a, b = ct.b, f = ct.f;
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

}

function createGeodesicPolygon(origin, radius, sides) {
  var latlon = origin; //leaflet equivalent
  var angle;
  var new_lonlat, geom_point;
  var points = [];

  for (var i = 0; i < sides; i++) {
    angle = (i * 360 / sides);
    new_lonlat = destinationVincenty(latlon, angle, radius);
    geom_point = L.latLng(new_lonlat.lng, new_lonlat.lat);

    points.push(geom_point);
  }

  return L.polygon(points);
}


$(function() {
  $("#alert-image.modal").appendTo("body").modal("show");

  var activateRemarquable = function() {
    // activation des infos élément remarquables
    var checked = $("#complements-dialog input[name='remarquable']").is(":checked");
    $("#dialog-remarquable-infos input").prop('disabled', !checked);
    $("#dialog-remarquable-infos").toggleClass('inactive', !checked);
  }

  var bounds;
  // carte de pointage (dialogue)
  if ($("#dialog-map").length > 0) {
    var drawnLayer;
    var dlg_map = base_map("dialog-map", site.ep_id, 'IGN topo');
    dlg_map.pm.addControls({
      position: 'topleft'
    });
    dlg_map.on('pm:create', function(evt) {
      if(drawnLayer) dlg_map.removeLayer(drawnLayer);
      drawnLayer = evt.layer;
      var geojson = evt.layer.toGeoJSON();
      if (evt.shape == 'Circle') {
        geojson = createGeodesicPolygon(evt.layer.getLatLng(), evt.layer.getRadius(), 20).toGeoJSON();
      }
      $("#complements-dialog input[name='geom']").val(JSON.stringify(geojson));
    });

    $("#collapseMap").on("shown.bs.collapse", function() {
      dlg_map.invalidateSize();
      dlg_map.fitBounds(bounds);
    });
  }


  $(".rubrique-collapse")
    .on("show.bs.collapse", load_content)
    .on("change", "input[name='caracteristiques[]']", function(evt) {
    // affichage options remarquable
      var $chkbox = $(this);
      var id = $chkbox.val();
      $chkbox.parents('.choix-container').find('.remarquable-control')
        .toggleClass('checked', $chkbox.is(':checked'));
  }).on('click', '.remarquable-edit', function() {
    // affichage du sous-formulaire remarquable
    var $cont = $(this).parents('.choix-container');
    var $mymodal = $("#complements-dialog");
    $mymodal.data('qcm-id', $cont.data('qcm-item-id'));

    $.each($cont.find("input[type='hidden']"), function(i, elt) {
      var fieldName = $(elt).attr('name').slice(0, -2);
      var $form_field = $mymodal.find("input[name='" + fieldName + "']");
      var val = $(elt).val();
      if ($form_field.prop('type') == 'checkbox') {
        $form_field.prop('checked', Boolean(val));
      } else {
        $form_field.val(val);
      }
    });

    $mymodal.find("textarea[name='remarquable_info']").val($cont.find("input[name='remarquable_info[]']").val());

    activateRemarquable();

    if (drawnLayer) dlg_map.removeLayer(drawnLayer);
    var geom = $("#complements-dialog input[name='geom']").val();
    if (geom) {
      drawnLayer = L.geoJSON(JSON.parse(geom));
      dlg_map.addLayer(drawnLayer);
    }

    $mymodal.modal("show");

    dlg_map.invalidateSize();
    dlg_map.fitBounds(bounds);

    return false;
  }).on('click', '.photo-remove-button', function() {
    // suppression de photos
    var id = $(this).data('photo_id');
    var $cont = $(this).parents('.photo-thumbnail');
    if (window.confirm("Voulez-vous vraiment supprimer cette photo ?")) {
      $.get(site_url('site/suppr_photo/' + id), function(data) {
        $cont.remove();
      });
    }
    return false;
  });

  $("#complements-dialog input[name='remarquable']").change(activateRemarquable);

  $("#complements-dialog #button-ok").click(function() {
    // fermeture du dialogue et transmission des données
    var $mymodal = $("#complements-dialog");
    var id = $mymodal.data('qcm-id');
    var $cont = $("#choix-container-" + id);
    $mymodal.find('input').each(function(i, elt) {
      var $elt = $(elt);
      var name = $elt.attr('name');
      var $hiddenField = $cont.find("input[name='" + name + "[]']");
      if ($elt.attr('type') == 'checkbox') {
        if ($elt.is(':checked')) {
          $hiddenField.val(id);
        } else {
          $hiddenField.removeAttr('value');
        }
      } else {
        $hiddenField.val($elt.val());
      }

    });
    $cont.find("input[name='remarquable_info[]']").val($mymodal.find("[name='remarquable_info']").val());
  });

  // TODO : doit-on supprimer le contenu quand ça collapse ?

  // traitement du formulaire
  $(".rubrique")
    .on("submit", "form", submit_form)
    .on("click", ".button-save", submit_form)
    .on("click", ".button-cancel", load_content)
    .on("click", ".button-edit-form", load_form);


  // CARTO
  if ($('#map-main').length > 0) {
    var map = base_map('map-main', site.ep_id);

    if (map.monosite != 't') {
      $.get(site_url("carto/site_geom/" + site.id), function(data) {
        if (data.features[0].geometry) {
          var vectLayer = L.geoJSON(data, {pmIgnore: true}).addTo(map).bringToBack();
          bounds = vectLayer.getBounds();
          if (dlg_map) {
            vectLayer.addTo(dlg_map);
            dlg_map.fitBounds(bounds);
          }
          map.fitBounds(bounds);
        }
      });
    }
  }
});
