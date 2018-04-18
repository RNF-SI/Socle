<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<p>Quelles morphologies littorales de côtes rocheuses sont-elles observables
sur votre territoire&nbsp;?</p>
<?php
  $question = 'Q2.11';
  echo qcm_caracteristiques($ep->caracteristiques[$question]);
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''),
    'Autres éléments descriptifs des morphologies de côtes rocheuses observables sur votre territoire, non mentionnés dans la liste ci-dessus. Nommer et décrire si besoin&nbsp;?');
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
