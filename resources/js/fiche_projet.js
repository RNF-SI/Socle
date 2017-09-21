// fonctions de chargement des composants RubriquesController

$(function() {
  $(".rubrique .collapse").on("show.bs.collapse", function() {
    var id_rubrique = $(this).parents(".rubrique").attr('id');
    var container  = $(this).find(".rubrique-content");
    $.get(site_url("site/rubrique_content/" + entite_id + "/" + id_rubrique + '/' + type_rubrique), function(data) {
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
    $.get(site_url("site/rubrique_form/" + entite_id + "/" + id_rubrique + '/' + type_rubrique), function(data) {
      var form = $(data);
      $(".rubrique#" + id_rubrique + " .rubrique-content").empty().append(form);
    });
  });


  // carto
  if ($('#map-main').length > 0) {
    var map = base_map('map-main', espace_protege.code_national_ep);
  }

});
