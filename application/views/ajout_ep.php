<h2>Nouvel espace protégé</h2>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>

<form class="form-horizontal" action="<?= site_url(isset($ep) ? 'espace/modification/' . $ep->id : 'espace/creation') ?>" method="POST">
  <?= form_input('code_national_ep', 'Identifiant MNHN de l\'espace*&nbsp;:<span class="small italic"> (cet identifiant peut être trouvé sur
  <a href="https://inpn.mnhn.fr/accueil/recherche-de-donnees/espaces-proteges" target="_blank">le site de l\'INPN</a>)</span>', isset($ep) ? $ep->code_national_ep : NULL) ?>

  <?= form_input('nom', 'Nom :', isset($ep) ? $ep->nom : '') ?>
  <?= form_select('type', 'type d\'espace :', [
      'RNN' => 'Réserve naturelle nationale',
      'RNR' => 'Réserve naturelle régionale',
      'RNC' => 'Réserve naturelle de Corse',
      'autre' => 'autre'
    ]) ?>
  <?= form_checkbox('monosite', 'Cet espace ne contient qu\'un seul site (l\'analyse portera sur l\'ensemble du périmètre de l\'espace protégé)',
    isset($ep) ? $ep->monosite == 't' : TRUE) ?>
  <?= form_text('geom', 'Géométrie au format WKT ou GeoJson :', isset($ep) ? $ep->geom : '') ?>
  <?= form_select('group_id', 'Groupe d\'utilisateur à associer à l\'entité :', $groupes, isset($ep) ? $ep->group_id : min(array_keys($groupes))) ?>
  <p><a href="<?= site_url('utilisateurs/creation_groupe') ?>">Créer un nouveau groupe</a></p>
  <?= form_submit() ?>
</form>

<div id="map"></div>

<script src="<?= base_url('resources/js/ajout_ep.js') ?>"></script>
<script>
  var id_ep = <?= isset($ep) ? $ep->id : 'null' ?>;
</script>
