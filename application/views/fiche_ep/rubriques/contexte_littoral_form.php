<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<?= validation_errors('<div class="alert alert-warning">', '</div>') ?>
<h3>Votre territoire se situe-t-il en :</h3>
<?php
  $question = 'Q1.3.1';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
?>
<h3>Peut-on y observer ?</h3>
<?= qcm_caracteristiques($ep->caracteristiques['Q1.3.2']); ?>
<h3>Aménagements littoraux :</h3>
<?= qcm_caracteristiques($ep->caracteristiques['Q1.3.3']); ?>
<?php
  $question = 'Q1.3.3';
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''), 'Autres éléments descriptifs du
contexte littoral et marin de la réserve
non mentionnés dans la liste ci-dessus.
Nommer et décrire si besoin ?<br />
Nature du substratum rocheux des
fonds marins ?
Nommer et décrire ?');
  echo field_commentaires($rubrique, set_value('commentaire',empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
