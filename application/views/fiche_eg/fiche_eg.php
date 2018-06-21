<script>
  var entite_id = <?= $eg->id ?>;
  var id_site = <?= $site->id ?>;
  var id_ep = <?= $site->ep_id ?>;
  var type_rubrique = 'EG';
  var point_coords = <?= $eg->geojson ?>;
</script>
<?php if ($editable): ?>
<p>
	<div class="btn-group">
		<a href="<?= site_url('site/ajout_eg/'.$site->id.'/'.$eg->id) ?>" class="btn btn-primary">
			<span class="glyphicon glyphicon-pencil"></span> Modifier</a>
		<a href="<?= site_url('site/ajout_eg/'.$site->id) ?>" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Créer une nouvelle entité</a>
	</div>
</p>
<?php endif; ?>
<h1><?= $eg->intitule ?></h1>
<p>Pour le site <strong>
  <a href="<?= site_url('site/fiche_site/' . $site->id) ?>"><?= $site->nom ?></a>
</strong></p>
<div class="last_modified">Modifié le <?= date('d/m/Y', strtotime($eg->last_modified)) ?>
  par <?= $this->auth->user($eg->modified_by_userid)->row()->username ?>.</div>
<div id="map"></div>
<div>
    <h3>Identification sur la carte géologique</h3>
    <p>code <?= $eg->code ?> : <?= $eg->intitule ?></p>
    <p>Âge des roches : <?= $eg->ere_geol_label ?></p>
</div>
<?php
  $this->load->view('fiche_site/base_rubrique', [
    'titre' => 'Photos, documents',
    'ep' => $site,
    'id_rubrique' => 'points_de_vue'
  ]);
  ?>
<h3>Objets remarquables : Affleurements, points de vue...</h3>
<h4>Affleurements</h4>
<p><?= $eg->quantite_affleurements ?><br />
  <?= $eg->affleurements_accessibles ? 'Affleurements accessibles' : 'Affleurements inaccessibles' ?>
</p>
<div>
  <h4>Objets identifiés :</h4>
  <table class="table">
  <?php foreach ($eg->affleurements as $affl): ?>
    <tr><td><?= $affl->nom ?></td>
      <td><?= $affl->type ?></td>
      <td><?= $affl->description ?></td>
      <td><?php if ($editable): ?>
        <a href="<?= site_url('site/modification_affleurement/' . $affl->id . '/' . $eg->id) ?>" title="modifier"><span class="glyphicon glyphicon-edit"> </span></a>
      <?php endif; ?></td></tr>
  <?php endforeach; ?>
</table>
<?php if ($editable): ?>
  <a href="<?= site_url('site/ajout_affleurement/' . $eg->id) ?>" class="btn btn-primary">Ajouter et décrire un affleurement</a>
<?php endif; ?>
</div>
<?php

$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Nature des roches',
  'ep' => $site,
  'id_rubrique' => 'nature_roches']);

$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Minéraux et cristaux',
  'ep' => $site,
  'id_rubrique' => 'mineraux']);

$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Fossiles et fossilisation',
  'ep' => $site,
  'id_rubrique' => 'fossiles']);

$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Structures et figurés rocheux particuliers',
  'ep' => $site,
  'id_rubrique' => 'structures_rocheuses_particulieres']);

?>

<h3>Perméabilité des terrains</h3>
<p><?= $eg->permeabilite ?><br />
  <?= $eg->presence_aquifere ? 'La formation contient un aquifère' : 'La formation ne contient pas d\'aquifère' ?>
  <br /><?= $eg->niveau_sources ? 'La formation correspond à un niveau de sources' : 'La formation ne correspond pas à un niveau de sources' ?>
</p>

<?php if(!empty($eg->complements)): ?>
<h3>Informations complémentaires</h3>
<p><?= $eg->complements ?></p>
<?php endif; ?>
