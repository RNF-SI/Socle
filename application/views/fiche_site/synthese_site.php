<script>
  var site_id = <?= $site->id ?>;
</script>
<div class="container synthese">
<h1><?= $site->nom ?></h1>
Fiche de synthèse
<p><a href="<?= site_url('site/fiche_site/' . $site->id) ?>">Fiche détaillée</a></p>
<div class="export-buttons">
  <a href="<?= site_url("site/export_synthese/$site->id/docx") ?>">Export au format Word</a>
</div>
<div id="map"></div>
<div id="photo_gallery" class="card-deck">
  <?php foreach ($elements['photos'] as $phot): ?>
    <div class="card">
      <div class="card-body">
        <div class="thumbnail">
          <a href="<?= base_url('photos/' . $phot['url']) ?>"><img src="<?= $this->image_lib->thumbnail_url($phot['url'], 200, $phot['type']) ?>" /></a>
          <div class="caption"><?= $phot ['legende'] ?></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php
  // Structuration du contenu
  $structure = load_structure();
?>

<table class="table table-bordered table-synthese">
  <tbody>
  <?php foreach ($structure['site'] as $chapitre): ?>
    <tr>
      <td rowspan="<?= count($chapitre['rubriques']) ?>"><?= $chapitre['titre'] ?></td>
      <?php foreach($chapitre['rubriques'] as $i=>$rubrique): ?>
      <?php if ($i > 0) print "<tr>"; ?>
        <td><?=$rubrique['titre'] ?></td>
        <td><?php foreach ($rubrique['qcms'] as $q => $titre) {
          print liste_caracteristiques($elements['qcms'], $q, $titre, TRUE);
        } ?></td>
        </tr>
      <?php endforeach;
    endforeach;
 ?>
  </tbody>
</table>

<h2>Identification des terrains, des roches et des fossiles</h2>
<h3>Entités géologiques identifiées</h3>

<?php foreach ($elements['egs'] as $eg): ?>
  <h4><?= $eg['nom'] ?></h4>
  <table class="table table-bordered table-synthese">
    <tbody>
    <?php foreach ($structure['entite'] as $rubrique): ?>
      <tr>
          <td><?=$rubrique['titre'] ?></td>
          <td><?php foreach ($rubrique['qcms'] as $q => $titre) {
            print liste_caracteristiques($eg['qcms'], $q, $titre, TRUE);
          } ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<?php endforeach; ?>


</div>
