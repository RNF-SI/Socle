<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<p>Quelles morphologies karstiques sont observables sur votre territoire ?</p>
<h4>Exokarst</h4>
<?php
  $question = 'Q2.6.1';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  ?>
<h4>Endokarst</h4>
<?php
  $question = 'Q2.6.2';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''),
    'Autres éléments descriptifs des morphologies karstiques observables sur votre territoire, non mentionnés dans la liste ci-dessus. Nommer et décrire si besoin ?');
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
