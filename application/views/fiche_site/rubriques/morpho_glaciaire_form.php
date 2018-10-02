<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<p>Quelles morphologies glaciaires actuelles ou héritées sont observables sur votre réserve ?</p>
<h4>Morphologie glaciaire active / actuelle</h4>
<?php
  $question = 'Q2.7.1';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  ?>
<h4>Morphologie glaciaire héritée</h4>
<?php
  $question = 'Q2.7.2';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''),
    'Autres éléments descriptifs des morphologies glaciaires observables sur votre territoire, non mentionnés dans la liste ci-dessus. Nommer et décrire si besoin ?');
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
