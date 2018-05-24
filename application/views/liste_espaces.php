<div>
  <ul>
    <?php foreach ($espaces as $ep): ?>
      <li><a href="<?= site_url('espace/fiche_espace/'. $ep->id) ?>">
        <?= $ep->nom ?></a></li>
    <?php endforeach;  ?>
  </ul>
</div>
<div>
  <a href="<?= site_url('espace/ajout_ep') ?>" class="btn btn-primary">Ajouter</a>
</div>
