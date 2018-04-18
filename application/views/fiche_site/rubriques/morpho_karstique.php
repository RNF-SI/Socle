<div class="rubrique-description">
    <p>Dolines, avens, grottes, stalactites, lapiez, résurgences, etc. On entend par morphologies
karstiques toutes les formes de surface – exokarst – ou souterraines
– endokarst – liées à l’action dissolvante de l’eau sur les roches carbonatées :
calcaires et dolomies. Il s’agit donc de structures d’érosion particulières se traduisant
principalement par un univers souterrain, terrain d’aventure des spéléologues.</p>
  </div>
<p>Les éléments suivants peuvent être observés&nbsp;:</p>
<?php
  if (!empty($caracteristiques['Q2.6.1']))
    echo '<h4>Exokarst</h4>' . liste_caracteristiques($caracteristiques, 'Q2.6.1');
  if (!empty($caracteristiques['Q2.6.2']))
    echo '<h4>Endokarst</h4>';
  echo liste_caracteristiques($caracteristiques, 'Q2.6.2');

  echo complement($complements, 'Q2.6.2');
  echo commentaire($commentaire->commentaire);
?>
