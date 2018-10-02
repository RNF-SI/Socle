<?= form_open('site/rubrique_form/' . $site->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<h3>Sur votre territoire, peut-on observer ?</h3>
<?php
  $question = 'Q1.4';
  echo qcm_caracteristiques($site->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($site->complements[$question]) ? $site->complements[$question]->elements : ''));
  echo field_commentaires($rubrique, set_value('commentaire', empty($site->commentaire) ? $site->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
