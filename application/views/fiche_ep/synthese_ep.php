<?php
  function reponses($data, $question, $titre) {
    if ((! isset($data[$question])) || count($data[$question]) == 0) {
      return;
    }
    echo "<h4>$titre&nbsp;:</h4>";
    echo '<ul>';
    foreach ($data[$question] as $q) {
        echo  '<li>' . $q->label . '</li>';
    }
    echo '</ul>';
  }
 ?>

<h1><?= $ep->nom_ep ?></h1>
Fiche de synthèse
<p><a href="<?= site_url('site/fiche_site/' . $ep->id) ?>">Fiche détaillée</a></p>
<h2>Approche géographique</h2>
<h3>Points de vue</h3>
[A FAIRE]

<h3>Contexte géographique général</h3>
<?php
  reponses($caract, 'Q1.1', 'Type de territoire');
  reponses($caract, 'Q1.1.2', 'Éléments présents');
?>

<h3>Contexte hydrographique général</h3>
<?php
  reponses($caract, 'Q1.2', 'Éléments hydrographiques observables sur le territoire');
 ?>

<h3>Contexte général littoral et marin</h3>
<?php
  reponses($caract, 'Q1.3', 'Éléments observables sur le territoire');
 ?>

<h3>Contexte anthropique général - Aménagements<h3>
  <?php
    reponses($caract, 'Q1.4', 'Aménagements observables sur le territoire');
   ?>

 <h2>Aspects morphologiques et structuraux des terrains</h2>
 <h3>Contexte géologique régional et local</h3>
 <?php
   reponses($caract, 'Q2.0', 'Éléments géologiques observables sur le territoire');
   ?>

 <h3>Grandes structures géologiques régionales</h3>
 <?php
   reponses($caract, 'Q2.1', 'Éléments géologiques observables sur le territoire');
   ?>

<?php var_dump($caract) ?>
