<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique . '/EG',
['class' => 'form-horizontal']) ?>
<p>Pour chaque formation géologique: préciser la présence de « minéraux particuliers
», macro ou microscopiques.<br />
Pour ce faire se référer à la notice de la carte, à d’autres sources documentaires,
à la compétence d’un spécialiste.</p>
<p>Identifier, selon la liste typologique suivante les minéraux et cristaux observables
sur votre territoire et présentant un intérêt particulier à un titre ou à un autre :
spécificité, patrimonialité, pédagogie, taille, esthétique… Ne pas mentionner les
minéraux courants comme les grains de quartz d’un granite, par exemple !</p>
<?php
  $question = 'Q3.C';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''));
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
