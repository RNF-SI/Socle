<h4>Autres cartes géologiques réalisées sur le territoire :</h4>
<p>Le territoire a-t-il été cartographié géologiquement sur un (des) autre document ?<br />
A quelle échelle ?</p>
<?= form_text('autres_cartes_geol', 'Nom du (des) document ? Echelle(s) ? Comment se le (les) procurer ?',
  $ep->autres_cartes_geol) ?>
<h4>Observations réalisées sur le terrain :</h4>
<p>Dans un premier temps, incrémenter simplement le fait que cette démarche
scientifique a été réalisée. OUI / NON<br />
Puis répondre en fonction des possibilités :<ul>
<li>Qui a procédé à cette démarche ? Préciser</li>
<li>Campagne(s) de terrain – Préciser</li>
<li>Des rapports ont-ils été produits ? Préciser</li>
<li>Lister quelques documents décrivant tout ou partie de la géologie du
territoire.</li>
</ul></p>
<p style="font-style:italic">NB : Pour certaines réserves à fort potentiel géologique, le nombre de communications,
mémoires, sujets de recherche et thèses est tel que ce recensement est
quasiment impossible ! Il faut alors adapter les réponses et ne mentionner que
les documents essentiels et très référents.</p>
<?=form_text('observations_in_situ', '', $ep->observations_in_situ) ?>
