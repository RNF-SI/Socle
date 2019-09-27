<?= form_open('site/rubrique_form/' . $site->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>
<h3>Votre territoire est-il ? / correspond-il à ?</h3>
<?php
  /*echo form_input('nombre_morcellement', 'En combien de parties le territoire est-il fractionné ? (1 = non morcellé)',
    set_value('nombre_morcellement', $site->nombre_morcellement ?: 1)); */
  echo qcm_caracteristiques($site->caracteristiques['Q1.0']);
?>
<h3>Votre territoire se situe-t-il en&nbsp;:</h3>
<?php
  echo qcm_caracteristiques($site->caracteristiques['Q1.1']);
?>
<h3>Votre territoire montre-t-il&nbsp;:</h3>
<?php
  $question = 'Q1.1.2';
  echo qcm_caracteristiques($site->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($site->complements[$question]) ? $site->complements[$question]->elements : ''));
  echo field_commentaires($rubrique, set_value('commentaire', empty($site->commentaire) ? $site->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
