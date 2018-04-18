<p>Bienvenue sur la base de données géologique de Réserves Naturelles de France&nbsp;!</p>
<div class="alert alert-warning">
  <strong>Attention</strong>
  <p>Ce site est actuellement en construction. Aucune donnée n'est à considérer comme définitive.</p>
</div>
<h2>Liste des espaces enregistrés</h2>
<ul>
  <?php foreach($espaces as $ep): ?>
    <li><a href="<?= site_url('Espace/fiche_espace/'.$ep->id) ?>"><?= $ep->nom ?></a></li>
  <?php endforeach; ?>
</ul>
<?php if ($this->auth->logged_in()): ?>
<a href="<?=site_url('espace/creation') ?>" class="btn btn-primary">Ajouter un espace</a>
<?php endif; ?>
