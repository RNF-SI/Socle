<script>
  var site_id = <?= $site->id ?>;
</script>
<div class="container synthese">
<h1><?= $site->nom ?></h1>
Fiche de synthèse
<p><a href="<?= site_url('site/fiche_site/' . $site->id) ?>">Fiche détaillée</a></p>
<div id="map"></div>
<div id="photo_gallery" class="row">
  <?php foreach ($elements['photos'] as $phot): ?>
    <div class="col-md-3">
      <div class="thumbnail">
        <a href="<?= base_url('photos/' . $phot['url']) ?>"><img src="<?= $this->image_lib->thumbnail_url($phot['url'], 200, $phot['type']) ?>" /></a>
        <div class="caption"><?= $phot ['legende'] ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<h2>Approche géographique</h2>
<h3>Contexte géographique général</h3>
<?php
  print liste_caracteristiques($elements['qcms'], 'Q1.1', 'Type de territoire');
  print liste_caracteristiques($elements['qcms'], 'Q1.1.2', 'Éléments présents');
?>

<h3>Contexte hydrographique général</h3>
<?php
  print liste_caracteristiques($elements['qcms'], 'Q1.2', 'Éléments hydrographiques observables sur le territoire', TRUE);
 ?>

<h3>Contexte général littoral et marin</h3>
<?php
  print liste_caracteristiques($elements['qcms'], 'Q1.3.1', 'Domaines dans lesquels se situe le site', TRUE);
  print liste_caracteristiques($elements['qcms'], 'Q1.3.2', 'Éléments observables sur le territoire', TRUE);
  print liste_caracteristiques($elements['qcms'], 'Q1.3.3', 'Aménagements littoraux', TRUE);
 ?>

<h3>Contexte anthropique général - Aménagements<h3>
  <?php
    print liste_caracteristiques($elements['qcms'], 'Q1.4', 'Aménagements observables sur le territoire', TRUE);
   ?>

 <h2>Aspects morphologiques et structuraux des terrains</h2>
 <h3>Contexte géologique régional et local</h3>
 <?php
   print liste_caracteristiques($elements['qcms'], 'Q2.0', 'Éléments géologiques observables sur le territoire', TRUE);
   ?>

 <h3>Grandes structures géologiques régionales</h3>
 <?php
   print liste_caracteristiques($elements['qcms'], 'Q2.1', 'Structures géologiques observables sur le territoire', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.2', 'Structures géologiques à l\'échelle du territoire', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.4', 'Structures et morphologies liées au volcanisme', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.5', 'Morphologies liées à l’érosion générale', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.6.1', 'Morphologies karstiques : Exokarst', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.6.2', 'Morphologies karstiques : Endokarst', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.7', 'Morphologies glaciaires', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.8', 'Morphologies alluvionnaires des cours d’eau', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.9', 'Plages littorales : sable, galets et vase', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.10', 'Systèmes dunaires littoraux', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.11', 'Côtes rocheuses', TRUE);
   print liste_caracteristiques($elements['qcms'], 'Q2.12', 'Structures et figurés rocheux particuliers à petite et moyenne échelle', TRUE);
   ?>

<h2>Identification des terrains, des roches et des fossiles</h2>
<h3>Entités géologiques identifiées</h3>
<table class="table table-default table-synthese">
  <tbody>
    <?php foreach ($elements['egs'] as $eg): ?>
      <tr>
        <td><strong><?= $eg['nom'] ?></strong></td>
        <td><?= $eg['age_roches'] ?></td>
        <td>
          <h4>Nature des roches</h4>
          <?php
          print liste_caracteristiques($eg['qcms'], 'Q3.B.1', 'Roches sédimentaires', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.B.2', 'Roches métamorphiques', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.B.3', 'Roches magmatiques plutoniques', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.B.4', 'Roches magmatiques volcaniques', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.B.5', 'Sédiments meubles - Sables', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.B.6', 'Sédiments meubles - Galets / graviers', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.B.7', 'Sédiments meubles - Vases / sédiments très fins', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.B.8', 'Matériaux d\'origine anthropique', TRUE);
          print liste_caracteristiques($eg['qcms'], 'Q3.C', 'Minéraux et cristaux', TRUE);
          for ($i=1; $i<=20; $i++) {
            print liste_caracteristiques($eg['qcms'], 'Q3.D.' . $i, NULL, TRUE);
            //if ($rep != '<i>&lt;Aucun élément&gt;</i>') print $rep;
          }
          ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</div>
