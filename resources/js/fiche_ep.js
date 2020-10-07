$(function() {
  var bm = new BaseMap('map', {id_ep: id_ep});

  $('.suppression-site').click(function() {
    if (confirm("Voulez-vous vraiment supprimer ce site et tous les éléments associés ?")) {
      window.location.href = site_url('site/suppr_site/' + site_id);
    }
    return false;
  });
});
