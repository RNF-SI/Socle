<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>
<h3>Votre territoire est-il ? / correspond-il à ?</h3>
<?php
  /*echo form_input('nombre_morcellement', 'En combien de parties le territoire est-il fractionné ? (1 = non morcellé)',
    set_value('nombre_morcellement', $ep->nombre_morcellement ?: 1)); */
  echo form_checkbox('site_anthropique', 'un site anthropique');
?>
<h3>Votre territoire se situe-t-il en&nbsp;:</h3>
<?php
  echo qcm_caracteristiques($qcms['Q1.1'], set_value('caracteristiques', element( 'Q1.1', $ep->caracteristiques)));
?>
<h3>Votre territoire montre-t-il&nbsp;:</h3>
<?php
  $question = 'Q1.1.2';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''));
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
