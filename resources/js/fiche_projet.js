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
    $("#coche-complement-" + id).toggle(this.checked);
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
