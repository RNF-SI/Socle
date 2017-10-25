<script>
  var entite_id = <?= $eg->id ?>;
  var id_ep_ref = '<?= $ep->code_national_ep ?>';
  var type_rubrique = 'EG';
  var point_coords = '<?= $eg->coords ?>';
</script>
<p>
	<div class="btn-group">
		<a href="<?= site_url('site/ajout_eg/'.$eg->espace_protege_id.'/'.$eg->id) ?>" class="btn btn-primary">
			<span class="glyphicon glyphicon-pencil"></span> Modifier</a>
		<a href="<?= site_url('site/ajout_eg/'.$eg->espace_protege_id) ?>" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Créer une nouvelle entité</a>
	</div>
</p>
<h1><?= $eg->intitule ?></h1>
<p>Pour l'espace naturel <strong>
  <a href="<?= site_url('site/fiche_site/' . $ep->id) ?>"><?= $ep->nom_ep ?></a>
</strong></p>
<div class="row">
  <div class="col-sm-7">
    <h3>Identification sur la carte géologique</h3>
    <p>code <?= $eg->code_eg ?> : <?= $eg->intitule_eg ?></p>
    <p>Âge des roches : <?= $eg->ere_geol_label ?></p>
  </div>
  <div class="col-sm-5">
    <div id="map" style="height:300px"></div>
  </div>
</div>
<h3>Aspect des affleurements</h3>
<p><?= $eg->quantite_affleurements ?><br />
  <?= $eg->affleurements_accessibles ? 'Affleurements accessibles' : 'Affleurements inaccessibles' ?>
</p>
<?php

$this->load->view('fiche_ep/base_rubrique', [
  'titre' => 'Nature des roches',
  'ep' => $ep,
  'id_rubrique' => 'nature_roches']);

$this->load->view('fiche_ep/base_rubrique', [
  'titre' => 'Minéraux et cristaux',
  'ep' => $ep,
  'id_rubrique' => 'mineraux']);

$this->load->view('fiche_ep/base_rubrique', [
  'titre' => 'Fossiles et fossilisation',
  'ep' => $ep,
  'id_rubrique' => 'fossiles']);

$this->load->view('fiche_ep/base_rubrique', [
  'titre' => 'Structures et figurés rocheux particuliers',
  'ep' => $ep,
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
