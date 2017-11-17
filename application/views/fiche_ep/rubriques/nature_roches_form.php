<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique . '/EG',
['class' => 'form-horizontal']) ?>
<p>Identifier, selon la liste typologique suivante, les roches et sédiments constituant
le sous-sol (affleurant ou non) de votre territoire. Les différentes informations
s’additionneront au fur et à mesure de l’analyse des différents terrains.</p>
<?php
  $struct = array(
    'Roches sédimentaires' => 'Q3.B.1',
    'Roches métamorphiques' => 'Q3.B.2',
    'Roches magmatiques plutoniques' => 'Q3.B.3',
    'Roches magmatiques volcaniques' => 'Q3.B.4',
    'Sédiment meubles' => array(
      'Sables - classe des arénites' => 'Q3.B.5',
      'Galets / graviers - classe des rudites' => 'Q3.B.6',
      'Vases/sédiments très fins : classe des pélites/lutites' => 'Q3.B.7'
    ),
    'Materiaux d’origine anthropique' => 'Q3.B.8',
  );

  foreach ($struct as $k => $v) {
    echo structReponsesForm($k, $v, 3, $ep->caracteristiques, $ep->complements);
  }

  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));

  echo form_submit();
  echo form_close();
 ?>
