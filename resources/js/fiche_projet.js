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

$(function() {
  $("#alert-image.modal").appendTo("body").modal("show");

  var activateRemarquable = function() {
    // activation des infos élément remarquables
    var checked = $("#complements-dialog input[name='remarquable']").is(":checked");
    $("#dialog-remarquable-infos input").prop('disabled', !checked);
    $("#dialog-remarquable-infos").toggleClass('inactive', !checked);
  }

  // carte de pointage (dialogue)
  if ($("#dialog-map").length > 0) {
    var drawnLayer;
    var dlg_map = new BaseMap("dialog-map", {id_ep: site.ep_id, currentBaseLayer: 'IGN topo', displayPopup: false});
    dlg_map.map.pm.addControls({
      position: 'topleft'
    });
    dlg_map.map.on('pm:create', function(evt) {
      if(drawnLayer) dlg_map.map.removeLayer(drawnLayer);
      drawnLayer = evt.layer;
      var geojson = evt.layer.toGeoJSON();
      if (evt.shape == 'Circle') {
        geojson = dlg_map.createGeodesicPolygon(evt.layer).toGeoJSON();
      }
      $("#complements-dialog input[name='geom']").val(JSON.stringify(geojson));
    });

    $("#collapseMap").on("shown.bs.collapse", function() {
      dlg_map.map.invalidateSize();
      dlg_map.zoomToInit();
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
    // affichage du sous-formulaire complements
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

    if (drawnLayer) dlg_map.map.removeLayer(drawnLayer);
    var geom = $("#complements-dialog input[name='geom']").val();
    if (geom) {
      drawnLayer = L.geoJSON(JSON.parse(geom));
      dlg_map.map.addLayer(drawnLayer);
    }

    $mymodal.modal("show");

    dlg_map.map.invalidateSize();
    dlg_map.zoomToInit();

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
    $mymodal.find('.item-control').each(function(i, elt) {
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
    $mymodal.find("#collapseMap").collapse("hide");
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
    var map = new BaseMap('map-main', {id_ep: site.ep_id, reductible: true});

    if (! map.options.monosite != 't') {
      var style = {
        color: 'blue'
      };
      map.addVectorLayer(site_url("carto/site_geom/" + site.id), style, function(lyr) {
        if (data.features[0].geometry) {
          lyr.setOptions({pmIgnore: true});
          lyr.bringToBack();
          var bounds = lyr.getBounds();
          if (dlg_map) {
            lyr.addTo(dlg_map.map);
            dlg_map.map.fitBounds(bounds);
          }
        }
      }, true);
    }
  }
});
