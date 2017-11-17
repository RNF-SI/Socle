<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>
<h3>Votre territoire se situe dans quel ?</h3>
<?php
  echo form_input('bassin_hydro_general', 'Bassin hydrographique général : ',
    set_value('bassin_hydro_general', $ep->bassin_hydro_general));
  echo form_input('bassin_hydro_rapproche', 'Bassin hydrographique rapproché : ',
    set_value('bassin_hydro_rapproche', $ep->bassin_hydro_rapproche));
?>
<h3>Sur votre territoire,  peut-on observer les éléments suivants&nbsp;?</h3>
<?php
  $question = 'Q1.2';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''));
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
