<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<h3>Sur votre territoire, peut-on observer ?</h3>
<?php
  $question = 'Q1.4';
  echo qcm_caracteristiques($qcms[$question], set_value('caracteristiques', element( $question, $ep->caracteristiques)));
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''));
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
