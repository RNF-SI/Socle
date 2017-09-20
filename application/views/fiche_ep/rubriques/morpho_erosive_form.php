<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<p>Identifier, selon la liste typologique suivante, les morphologies d’érosion
observables sur votre territoire.</p>
<?php
  $question = 'Q2.5';
  echo qcm_caracteristiques($qcms[$question], set_value('caracteristiques', element($question, $ep->caracteristiques)));
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''),
    'Autres éléments descriptifs des structures et morphologies d’érosion observables sur votre territoire, non mentionnés dans la liste ci-dessus. Nommer et décrire si besoin ?');
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
