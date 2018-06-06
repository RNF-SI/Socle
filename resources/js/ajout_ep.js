$(function() {
/*  $.get(site_url('carto/getINPNWFSLayers'), function(res) {
    $('#type_ep').empty();
    $.each(res, function(i, lyr) {
      var opt = $('<option>').attr('value', lyr.name).text(lyr.title);
      if (lyr.name == 'Reserves_naturelles_nationales') {
        opt.attr('selected', 'selected');
      }
      $('#type_ep').append(opt);
    });
  }) */

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
