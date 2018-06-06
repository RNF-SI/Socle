<h3>Ajout d'un affleurement pour l'entité <i><?= $eg->intitule ?></i></h3>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>
<p>Localiser l'affleurement sur la carte par un point</p>
<div id="map"></div>
<form class="form form-horizontal" method="POST" action="<?= site_url('site/ajout_affleurement/'.$eg->id . (is_null($affl) ? '' : '/'.$affl->id) ) ?>">
  <?= form_hidden('geom', set_value_obj('geom', $affl)) ?>
  <?= form_input('nom', 'Nom :', set_value_obj('nom', $affl)) ?>
  <p>Décrire l’affleurement : Typologie de l’affleurement (chaos, bancs, lapiaz, verticalité, horizontalité, etc. ...)
Nom du lieu si connu – nom de la carrière (par exemple).</p>
  <?= form_text('description', 'Description :', set_value_obj('description', $affl)) ?>
  <?= form_submit() ?>
</form>
<script>
  var site_id = <?= $site->id ?>;
</script>
