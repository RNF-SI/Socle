<?= form_open('site/rubrique_form/' . $site->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<h4>D'où proviennent les collections géologiques possédées par la réserve ?</h4>
<?php
  $question = 'Q3.2';
  echo qcm_caracteristiques($site->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($site->complements[$question]) ? $site->complements[$question]->elements : ''),
    'Autre provenance de collections&nbsp;:');
  echo field_commentaires($rubrique, set_value('commentaire', empty($site->commentaire) ? $site->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
