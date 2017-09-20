<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<p>Quels figurés rocheux particuliers (sédimentaires ou autres) sont-ils observables
sur votre territoire&nbsp;? Ce questionnement est repris en Q-3, associé à chaque entité
géologique précise du territoire.</p>
<?php
  $question = 'Q2.12';
  echo qcm_caracteristiques($qcms[$question], set_value('caracteristiques', element($question, $ep->caracteristiques)));
  echo liste_complement($question, set_value('complements', isset($ep->complements[$question]) ? $ep->complements[$question]->elements : ''),
    'Autres éléments descriptifs des structures et figurés rocheux observables sur votre territoire, non mentionnés dans la liste ci-dessus. Nommer et décrire si besoin&nbsp;?');
  echo field_commentaires($rubrique, set_value('commentaire', empty($ep->commentaire) ? $ep->commentaire->commentaire : ''));
  echo form_submit();
  echo form_close();
 ?>
