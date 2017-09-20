// fonctions de chargement des composants RubriquesController

$(function() {
  $(".rubrique .collapse").on("show.bs.collapse", function() { // à modifier pour ne pas tout charger
    var id_rubrique = $(this).parents(".rubrique").attr('id');
    var container  = $(this).find(".rubrique-content");
    $.get(site_url("site/rubrique_content/" + espace_protege.id + "/" + id_rubrique), function(data) {
      container.html(data);
    });
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
    $.get(site_url("site/rubrique_form/" + espace_protege.id + "/" + id_rubrique), function(data) {
      var form = $(data);
      $(".rubrique#" + id_rubrique + " .rubrique-content").empty().append(form);
    });
  });


  // carto
  if ($('#map-main').count > 0) {
    var mainMap = L.map('map-main').setView([46, 1], 7);
    var osmLayer = L.tileLayer('http://tile-{s}.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(mainMap);
    var baseLayers = {"OpenstreetMap": osmLayer};

    // contours de la réserve
    $.get(site_url("carto/espace_protege_geom/" + espace_protege.code_national_ep), function(data) {
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
  }

});
