<div class="rubrique-description">
    <p>Pour chaque formation géologique : rechercher la nature précise des roches et/ou
des alluvions, des sédiments constituants l’entité géologique, affleurant ou non.<br />
Pour ce faire, se référer à la notice de la carte, à d’autres sources documentaires,
à la compétence d’un spécialiste.</p>
  </div>
<p>Les roches suivantes ont été observées&nbsp;:</p>
<?php
  $struct = array(
    'Roches sédimentaires' => 'Q3.B.1',
    'Roches métamorphiques' => 'Q3.B.2',
    'Roches magmatiques plutoniques' => 'Q3.B.3',
    'Roches magmatiques volcaniques' => 'Q3.B.4',
    'Sédiments meubles' => array(
      'Sables - classe des arénites' => 'Q3.B.5',
      'Galets / graviers - classe des rudites' => 'Q3.B.6',
      'Vases/sédiments très fins classe des pélites/lutites' => 'Q3.B.7'
    ),
    'Materiaux d’origine anthropique' => 'Q3.B.8',
  );


  foreach ($struct as $k => $v) {
    echo structReponses($k, $v, 3, $caracteristiques, $complements);
  }

  echo commentaire($commentaire->commentaire);
?>
