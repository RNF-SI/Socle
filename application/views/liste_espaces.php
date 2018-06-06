<div>
  <ul>
    <?php foreach ($espaces as $ep): ?>
      <li><a href="<?= site_url('espace/fiche_espace/'. $ep->id) ?>">
        <?= $ep->nom ?></a></li>
    <?php endforeach;  ?>
  </ul>
</div>
<?php if ($this->auth->is_admin()): ?>
<div>
  <a href="<?= site_url('espace/creation') ?>" class="btn btn-primary">Ajouter</a>
</div>
<?php endif; ?>
