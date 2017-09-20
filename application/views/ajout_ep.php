<h2>Nouvel espace protégé</h2>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>
<p>La création d'un espace protégé se fait à partir des espaces pré-enregistrés.</p>
<div class="form-horizontal">
  <div class="form-group">
    <label class="control-label col-sm-2" for="select-ep">Choisir une réserve :</label>
    <div class="col-sm-10">
      <select id="select-ep" class="form-control">
        <?php foreach ($espaces_ref as $espace): ?>
          <option value="<?= $espace->id_mnhn ?>"><?= $espace->nom_site ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
</div>
<hr />
<form class="form-horizontal" action="<?= site_url('site/creation') ?>" method="POST">
  <?= form_hidden('code_national_ep') ?>
  <?= form_input('nom_ep', 'Nom :') ?>
  <?= form_select('type_ep', 'type d\'espace :', [
      'RNN' => 'Réseve naturelle nationale',
      'RNR' => 'Réseve naturelle régionale',
      'RNC' => 'Réseve naturelle de Corse'
    ]) ?>
  <?= form_input('surface_ep', 'Superficie :') ?>
  <?= form_submit() ?>
</form>

<script src="<?= base_url('resources/js/ajout_ep.js') ?>"></script>
