$(function() {
  var map = new BaseMap('map', {id_ep: id_ep});

  var search = /^FR\d{7}$/;
  $("#code_national_ep").on('keyup change', function() {
    var txt = $(this).val();
    if (txt.search(search) > -1) {
      $.get(site_url('api/get_espace_ref/' + txt), function(data) {
        $("#nom").val(data.nom_site);
        $("#geom").text(data.wkt);
      });
    }
  });
});
