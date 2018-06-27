// fonctions de chargement des composants RubriquesController

// TODO : empecher la fermeture du volet si des infos ont été saisies

function load_content(evt) {
  // contenu rubrique
  var $rubrique = $(evt.target).parents(".rubrique");
  var id_rubrique = $rubrique.attr('id');
  var $container  = $rubrique.find(".rubrique-content");
  $rubrique.find(".rubrique-toolbar .btn-group")
    .html('<button class="btn btn-primary button-edit-form"><span class="fas fa-edit"></span> Editer</button>');
  $.ajax(site_url("site/rubrique_content/" + entite_id + "/" + id_rubrique + '/' + type_rubrique), {
    success: function(data) {
      $container.html(data);
      activate_popover($rubrique);
    },
    error: function(xhr, status) {
      $container.html('<p class="error">Erreur de chargement :' + status + '</p>');
    }
  });
}

// soumission du formulaire
function submit_form(evt) {
  evt.preventDefault();
  $('.remarquable-dialog').remove();
  var $container = $(evt.target).parents(".rubrique").find(".rubrique-content");
  var $form = $container.find("form");

  var params = {
    url: $form.attr("action"),
    data: $form.serialize(),
    type: 'POST',
    success: function(response) {
        var messageBox = $container.siblings(".message");
        messageBox.empty();
        if (! response.success) { // echec de validation (retourne du json)
          messageBox.html('<div class="alert alert-warning">' + response.message + '</div>')
        } else {
          load_content(evt);
        }
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
  $("#alert-image.modal").modal("show");

  $(".rubrique-collapse")
    .on("show.bs.collapse", load_content)
    .on("change", "input[name='caracteristiques[]']", function(evt) {
    // affichage options remarquable
      var $chkbox = $(this);
      var id = $chkbox.val();
      $chkbox.parents('.choix-container').find('.remarquable-control')
        .toggleClass('checked', $chkbox.is(':checked'));
  }).on('click', '.coche-remarquable', function() {
    var $star = $(this);
    $star.parents('.remarquable-control').toggleClass('remarquable');
    if ($star.parents('.remarquable-control').hasClass('remarquable')) {
      var id = $star.parents('.choix-container').find("input[name='caracteristiques[]']").val();
      $star.parents('.choix-container').find("input[name='remarquable[]']").val(id);
    } else {
      $star.parents('.choix-container').find("input[name='remarquable[]']").removeAttr('value');
    }
    return false;
  }).on('click', '.remarquable-edit', function() {
    // affichage du sous-formulaire remarquable
    var $cont = $(this).parents('.choix-container');
    var checkbox = function(name, label, icon) {
      var val = $cont.find("input[name='" + name + "[]']").val();
      return '<div class="checkbox"><label><input type="checkbox" data-name="' + name + '" ' + (val ? 'checked' : '') + ' /> <span class="fas fa-'
        + icon + '"> </span> ' + label + '</label></div>';
    };
    var modal = '<div class="modal remarquable-dialog" role="dialog"><div class="modal-dialog"><div class="modal-content">'
      + '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4>Elément remarquable : informations complémentaires</h4></div>'
      + '<div class="modal-body"><form class="form-horizontal">'
      + '<p>Cet élément est intéressant d\'un point de vue :</p>'
      + checkbox('interet_scientifique', 'scientifique', 'flask')
      + checkbox('interet_pedagogique', 'pédagogique', 'chalkboard-teacher')
      + checkbox('interet_esthetique', 'esthétique', 'image')
      + checkbox('interet_historique', 'historique/culturel', 'book')
      + '<br /><div class="form-group"><label>Commentaires :</label><textarea name="remarquable_info" class="form-control">' + $cont.find("input[name='remarquable_info[]']").val()
      + '</textarea></div>'
      + '</form></div><div class="modal-footer"><button type="button" id="button-ok" class="btn btn-default" data-dismiss="modal">OK</button></div>'
      + '</div></div></div>';
    var $mymodal = $(modal).appendTo($cont);

    $mymodal.find('#button-ok').click(function() {
      var id = $cont.find("input[name='caracteristiques[]']").val();
      $mymodal.find('input').each(function(i, elt) {
        var name = $(elt).data('name');
        var $hiddenField = $cont.find("input[name='" + name + "[]']");
        if ($(elt).is(':checked')) {
          $hiddenField.val(id);
        } else {
          $hiddenField.removeAttr('value');
        }
      });
      $cont.find("input[name='remarquable_info[]']").val($mymodal.find("[name='remarquable_info']").val());
      //$mymodal.remove();
    })
    $mymodal.modal();
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
    var popup = L.popup({maxWidth: 200});
    map.on("click", function(evt) {
      if (map.getZoom() < 11) return false;
      popup.setLatLng(evt.latlng);
      getGeolInfo(map, evt, function(data) {
        var cont = '<p><b>Entité géologique :</b><br />' + data.notation + ' : <i>'
          + data.description + '</i></p>';
        popup.setContent(cont).openOn(map);
      });
    });

    if (map.monosite != 't') {
      $.get(site_url("carto/site_geom/" + site.id), function(data) {
        if (data.features[0].geometry) {
          var vectLayer = L.geoJSON(data).addTo(map).bringToBack();
          map.fitBounds(vectLayer.getBounds());
        }
      });
    }
  }

});
