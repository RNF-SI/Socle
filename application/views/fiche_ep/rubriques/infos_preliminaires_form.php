<?= form_open('site/rubrique_form/' . $ep->id . '/' . $rubrique,
['class' => 'form-horizontal']) ?>
<h4>Autres cartes géologiques réalisées sur le territoire&nbsp;:</h4>
<p>Le territoire a-t-il été cartographié géologiquement sur un (des) autre document&nbsp;?<br />
A quelle échelle&nbsp;?</p>
<?= form_text('autres_cartes_geol', 'Nom du (des) document&nbsp;? Echelle(s)&nbsp;? Comment se le (les) procurer&nbsp;?',
  $ep->autres_cartes_geol) ?>
<h4>Observations réalisées sur le terrain&nbsp;:</h4>
<p>Dans un premier temps, incrémenter simplement le fait que cette démarche
scientifique a été réalisée. OUI&nbsp;/&nbsp;NON<br />
Puis répondre en fonction des possibilités&nbsp;:<ul>
<li>Qui a procédé à cette démarche&nbsp;? Préciser</li>
<li>Campagne(s) de terrain – Préciser</li>
<li>Des rapports ont-ils été produits&nbsp;? Préciser</li>
<li>Lister quelques documents décrivant tout ou partie de la géologie du
territoire.</li>
</ul></p>
<p style="font-style:italic">NB&nbsp;: Pour certaines réserves à fort potentiel géologique, le nombre de communications,
mémoires, sujets de recherche et thèses est tel que ce recensement est
quasiment impossible&nbsp;! Il faut alors adapter les réponses et ne mentionner que
les documents essentiels et très référents.</p>
<?=form_text('observations_in_situ', '', $ep->observations_in_situ) ?>
<?php
  echo form_submit();
  echo form_close();
 ?>
