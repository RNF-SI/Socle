<h4>Feuilles des cartes géologiques au 1/50&nbsp;000 correspondant au territoire :</h4>
<?php
  if (empty($ep->feuilles_cartes)) {
    echo '<p>Aucune carte trouvée. Pour l\'outre-mer, voir les cartes disponibles auprès de BRGM.</p>';
  } else {
    echo '<ul>';
    foreach ($ep->feuilles_cartes as $feuille) {
      echo '<li>Feuille n°' . $feuille->numero . '&nbsp;: ' . $feuille->nom . '</li>';
    }
    echo '</ul>';
  }

if ($ep->autres_cartes_geol):
?>
<h4>Autres cartes géologiques réalisées sur le territoire :</h4>
<?= $ep->autres_cartes_geol ?>
<?php endif;

if (!empty($ep->observations_in_situ)):
?>
<h4>Observations réalisées sur le terrain :</h4>
<?= $ep->observations_in_situ ?>
<?php endif;

if (!empty($ep->liste_docs_geol)):
?>
<h4>autres documents décrivant la géologie de territoire :</h4>
<?= $ep->liste_doc_geol ?>
<?php endif; ?>
