$(function() {
  // pré-remplit les champs
  $('#select-ep').change(function() {
    var val = $(this).val();
    $.get(site_url("site/ajax_info_espace_ref/" + val), function(data) {
        $("input#nom_ep").val(data.nom_site);
        $("input[name=code_national_ep]").val(data.id_mnhn);
        $("input#surface_ep").val(data.surf_off);
        $("select#type_ep option").removeAttr("selected");
        $("select#type_ep option[value=" + data.code_r_enp + "]").attr('selected', 'selected');
    });
  });
});
