$(function() {
  $('.action-activate').click(function(evt) {
    var a_elt = $(this);
    var userid = a_elt.data('user-id');
    $.get(site_url('utilisateurs/toggle_activate/' + userid), function(response) {
      var message = "Utilisateur modifié avec succès";
      if (! response.success) {
        message = response.message;
      } else {
        a_elt.html(response.action == 'activated' ? 'désactiver' : 'activer');
        a_elt.parents('td').siblings('td.col-active').html(response.action == 'activated' ? 'oui' : 'non');        
      }
      $("#messages-global").html(message);
    })
  });
});
