<script>
  var id_ep = <?= $ep->id ?>;
  var geom_editable = <?= $ep->monosite ? 'false' : 'true' ?>;
</script>
<h2>Nouveau site pour <?= $ep->nom ?></h2>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>

<form class="form-horizontal" action="<?= site_url(isset($site) ? 'site/modification/' . $site->id . '/' . $ep->id : 'site/creation/' . $ep->id) ?>" method="POST">
  <?= form_hidden('ep_id', $ep->id) ?>
  <?= form_input('nom', 'Nom :', isset($site) ? $site->nom : '') ?>
  <?= form_checkbox('no_perimeter', 'Ce site n\'est pas cartographiable (par exemple pour décrire des collections ex-situ)',
    isset($site) && !$site->geom) ?>
  <?php
  if (!$ep->monosite) {
    echo form_text('geom', 'Géométrie au format WKT/GeoJSON :', isset($site) ? $site->geom : '');
  }
  echo form_submit();
  ?>
</form>

<div id="map"></div>
