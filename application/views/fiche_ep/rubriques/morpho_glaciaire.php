<div class="rubrique-description">
    <p>En région froide, à plus ou moins haute altitude, selon les latitudes et les climats,
se développent des glaciers qui s’écoulent vers les parties basses des
reliefs. Puissants rabots, ils sculptent les roches sur leur passage et façonnent
des morphologies caractéristiques. Parmi celles-ci, on peut distinguer celles
correspondant aux glaciers actuels, en particulier en haute montagne, et celles
héritées des époques des grandes glaciations quaternaires, dans des territoires
aujourd’hui totalement abandonnés par les glaces.</p>
  </div>
<p>Les éléments suivants peuvent être observés&nbsp;:</p>
<?php
  if (!empty($caracteristiques['Q2.7.1']))
    echo '<h4>Morphologie glaciaire active / actuelle</h4>' . liste_caracteristiques($caracteristiques, 'Q2.7.1');
  if (!empty($caracteristiques['Q2.7.2']))
    echo '<h4>Morphologie glaciaire héritée</h4>';
  echo liste_caracteristiques($caracteristiques, 'Q2.7.2');

  echo complement($complements, 'Q2.7.2');
  echo commentaire($commentaire->commentaire);
?>
