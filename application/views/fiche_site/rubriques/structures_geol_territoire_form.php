<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<p>Identifier, selon la liste typologique suivante, les structures et morphologies
générales liées à la tectonique et au mode de gisement des roches observables
sur votre territoire.</p>
<?php
  $question = 'Q2.2';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''),
    'Autres éléments descriptifs des structures géologiques et morphologies observables sur votre territoire, non mentionnés dans la liste ci-dessus. Nommer et décrire si besoin ?');
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
