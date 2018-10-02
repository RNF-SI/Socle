$(function() {
  $('.action-activate').click(function(evt) {
    var a_elt = $(this);
    var userid = a_elt.parents("tr").data('user-id');
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

  $('.action-groups').click(function(evt) {
    var $a_elt = $(this);
    var userid = $a_elt.parents("tr").data('user-id');
    $("#modal-groups .modal-body").html('');
    $("#modal-groups").modal();
    $.get(site_url('utilisateurs/user_groups/' + userid), function(response) {
      $("#modal-groups .modal-body").html(response);
    });
    return false;
  });

  $('.action-delete').click(function(evt) {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
      var $a_elt = $(this);
      var userid = $a_elt.parents("tr").data('user-id');
      $.get(site_url('utilisateurs/user_delete/' + userid), function(response) {
        if (response.success) {
          window.location.reload(true);
        } else {
          window.alert("L'utilisateur n'a pas pu être supprimé.");
        }
      });
      return false;
    }
  });

  $("#modal-groups").on('click', ".remove_group", function(evt) {
    var userid = $(this).parents("#user_groups").data('user-id');
    var groupid = $(this).data('group_id');
    $.get(site_url('utilisateurs/user_remove_group/' + userid + '/' + groupid), function(response) {
      if (response == "true") {
        $(evt.target).parents("li").remove();
      }
    })
  });

  $("#modal-groups").on('change', "#groups-add", function(evt) {
    var userid = $(this).parents("#user_groups").data('user-id');
    var groupid = $(this).val();
    var groupname = $("#groups-add :selected").text();
    $.get(site_url('utilisateurs/user_add_group/' + userid + '/' + groupid), function(response) {
      if (response == "true") {
        var $li = $('<li>').text(groupname).data('group_id', groupid);
        $("#user_groups ul").append($li);
      }
    });
  });
});
