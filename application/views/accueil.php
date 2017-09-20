<p>Bienvenue sur la base de données géologique de Réserves Naturelles de France&nbsp;!</p>
<h2>Liste des espaces enregistrés</h2>
<ul>
  <?php foreach($espaces as $ep): ?>
    <li><a href="<?= site_url('site/fiche_site/'.$ep->id) ?>"><?= $ep->nom_ep ?></a></li>
  <?php endforeach; ?>
</ul>
<a href="<?=site_url('site/creation') ?>" class="btn btn-primary">Ajouter un espace</a>
