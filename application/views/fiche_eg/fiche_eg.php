<script>
  var entite_id = <?= $eg->id ?>;
  var id_site = <?= $site->id ?>;
  var id_ep = <?= $site->ep_id ?>;
  var type_rubrique = 'EG';
  var point_coords = <?= $eg->geojson ?>;
</script>
<p>
	<div class="btn-group">
		<a href="<?= site_url('site/ajout_eg/'.$site->id.'/'.$eg->id) ?>" class="btn btn-primary">
			<span class="glyphicon glyphicon-pencil"></span> Modifier</a>
		<a href="<?= site_url('site/ajout_eg/'.$site->id) ?>" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Créer une nouvelle entité</a>
	</div>
</p>
<h1><?= $eg->intitule ?></h1>
<p>Pour le site <strong>
  <a href="<?= site_url('site/fiche_site/' . $site->id) ?>"><?= $site->nom ?></a>
</strong></p>
<div class="row">
  <div id="map"></div>
</div>
<div class="row">
  <div class="col-sm-7">
    <h3>Identification sur la carte géologique</h3>
    <p>code <?= $eg->code ?> : <?= $eg->intitule ?></p>
    <p>Âge des roches : <?= $eg->ere_geol_label ?></p>
  </div>
  <div class="col-sm-5">
    <div id="map" style="height:300px"></div>
  </div>
</div>
<h3>Affleurements</h3>
<p><?= $eg->quantite_affleurements ?><br />
  <?= $eg->affleurements_accessibles ? 'Affleurements accessibles' : 'Affleurements inaccessibles' ?>
</p>
<div>
  <h4>Affleurements identifiés :</h4>
  <table class="table">
  <?php foreach ($eg->affleurements as $affl): ?>
    <tr><td><?= $affl->nom ?></td>
      <td><a href="<?= site_url('site/modification_affleurement/' . $affl->id . '/' . $eg->id) ?>" title="modifier"><span class="glyphicon glyphicon-edit"> </span></td></tr>
  <?php endforeach; ?>
</table>
  <a href="<?= site_url('site/ajout_affleurement/' . $eg->id) ?>" class="btn btn-primary">Ajouter et décrire un affleurement</a>
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
  <?= $eg->presence_aquifere ? 'La formation contien un aquifère' : 'La formation ne contient pas d\'aquifère' ?>
  <br /><?= $eg->niveau_sources ? 'La formation correspond à un niveau de sources' : 'La formation ne correspond pas à un niveau de sources' ?>
</p>

<?php if(!empty($eg->complements)): ?>
<h3>Informations complémentaires</h3>
<p><?= $eg->complements ?></p>
<?php endif; ?>
