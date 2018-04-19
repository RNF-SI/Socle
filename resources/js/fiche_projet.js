// fonctions de chargement des composants RubriquesController

$(function() {
  $(".rubrique-collapse").on("show.bs.collapse", function(evt) {
    var id_rubrique = $(this).parents(".rubrique").attr('id');
    var container  = $(this).find(".rubrique-content");
    $.get(site_url("site/rubrique_content/" + entite_id + "/" + id_rubrique + '/' + type_rubrique), function(data) {
      container.html(data);
    });
  }).on("change", "input[name='caracteristiques[]']", function(evt) {
    var id = $(this).val();
    $(this).parents('.choix-container').find('.remarquable-control').toggleClass('hidden');
  }).on('click', '.coche-remarquable', function() {
    var $star = $(this);
    $star.toggleClass('active');
    if ($star.hasClass('active')) {
      var id = $star.parents('.choix-container').find("input[name='caracteristiques[]']").val();
      $star.parents('.choix-container').find("input[name='remarquable[]']").val(id);
    } else {
      $star.parents('.choix-container').find("input[name='remarquable[]']").removeAttr('value');
    }
    return false;
  }).on('click', '.remarquable-edit', function() {
    // affichage du sous-formulaire remarquable
    var $cont = $(this).parents('.choix-container');
    var $mymodal = $cont.find('.remarquable-dialog');
    if ($mymodal.length == 0) {
      var checkbox = function(name, label) {
        var val = $cont.find("input[name='" + name + "[]']").val();
        return '<label><input type="checkbox" data-name="' + name + '" ' + (val ? 'checked' : '') + ' />' + label + '</label>';
      };
      var modal = '<div class="modal remarquable-dialog" role="dialog"><div class="modal-dialog"><div class="modal-content">'
        + '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4>Elément remarquable : informations complémentaires</h4></div>'
        + '<div class="modal-body"><form class="form-horizontal">'
        + '<p>Cet élément est intéressant d\'un point de vue :</p>'
        + checkbox('interet_scientifique', 'scientifique')
        + checkbox('interet_pedagogique', 'pédagogique')
        + checkbox('interet_esthetique', 'esthétique')
        + checkbox('interet_historique', 'historique/culturel')
        + '</form></div><div class="modal-footer"><button type="button" id="button-ok" class="btn btn-default" data-dismiss="modal">OK</button></div>'
        + '</div></div></div>';
      $mymodal = $(modal).appendTo($cont);
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
        })
      })
    }
    $mymodal.modal();
  });

  // TODO : doit-on supprimer le contenu quand ça collapse ?

  // traitement du formulaire
  $(".rubrique").on("submit", "form", function(evt) {
    evt.preventDefault();
    var container = $(this).parents(".rubrique-content");
    $.post($(this).attr("action"), $(this).serialize(), function(response) {
        var messageBox = container.siblings(".message");
        messageBox.empty();
        if (typeof response == "object") { // echec de validation (retourne du json)
          messageBox.html('<div class="alert alert-warning">' + response.message + '</div>')
        } else {
          container.html(response);
        }

    });
    return false;
  });

  $(".button-edit-form").click(function(evt) {
    var id_rubrique = $(evt.target).parents(".rubrique").first().attr('id');
    $.get(site_url("site/rubrique_form/" + entite_id + "/" + id_rubrique + '/' + type_rubrique), function(data) {
      var form = $(data);
      $(".rubrique#" + id_rubrique + " .rubrique-content").empty().append(form);
    });
  });


  // carto
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

    $.get(site_url("carto/site_geom/" + site.id), function(data) {
      var vectLayer = L.geoJSON(data).addTo(map).bringToBack();
      map.fitBounds(vectLayer.getBounds());
    });
  }

});
