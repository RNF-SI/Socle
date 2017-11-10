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
  reponses($caract, 'Q1.1.2', 'Eléments présents');
?>

<h3>Contexte hydrographique général</h3>
<?php
  reponses($caract, 'Q1.2', 'Eléments hydrographiques observables sur le territoire');
 ?>

<?php var_dump($caract) ?>
