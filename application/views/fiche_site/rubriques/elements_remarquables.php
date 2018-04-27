<h3>Eléments identifiés comme remarquables</h3>
<?php
function format_criteres($elt) {
  $criteres = [];
  if ($elt->interet_scientifique) $criteres[] = 'scientifique';
  if ($elt->interet_pedagogique) $criteres[] = 'pédagogique';
  if ($elt->interet_historique) $criteres[] = 'historique / culturel';
  if ($elt->interet_esthetique) $criteres[] = 'esthétique';
  return implode(', ', $criteres);
}
?>
<h4>Eléments à l'échelle du site</h4>
<table class="table">
  <thead>
    <tr>
      <th>Elément</th>
      <th>Critères</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($caracts['site'] as $elt): ?>
      <tr>
        <td><?= $elt->label ?></td>
        <td><?= format_criteres($elt) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h4>Eléments à l'échelle des entités géologiques</h4>
<table class="table">
  <thead>
    <tr>
      <th>Entité</th>
      <th>Elément</th>
      <th>Critères</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($caracts['eg'] as $elt): ?>
      <tr>
        <td><a href="<?=site_url('site/fiche_entite_geol/' . $elt->eg_id) ?>"><?= $elt->intitule ?></a></td>
        <td><?= $elt->label ?></td>
        <td><?= format_criteres($elt) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
