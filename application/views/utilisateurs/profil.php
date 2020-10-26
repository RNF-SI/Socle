<h2>Profil de <?=$utilisateur->first_name ?> <?=$utilisateur->last_name ?></h2>


<p>
  <span class="fas fa-building" title="Organisme"></span>  <?php if (!empty($utilisateur->company)): ?><?=$utilisateur->company ?><?php else: ?>
  <span style="font-style: italic;">non renseigné</span><?php endif ?>
  <span class="fas fa-phone" title="Téléphone"></span>  <?php if (!empty($utilisateur->phone)): ?><?=$utilisateur->phone ?><?php else: ?>
  <span style="font-style: italic;">non renseigné</span><?php endif ?>
  <span class="fas fa-at" title="E-mail"></span>  <?php if (!empty($utilisateur->email)): ?><?=$utilisateur->email ?><?php else: ?>
  <span style="font-style: italic;">non renseigné</span><?php endif ?>
</p>

<h4>Est membre des groupes suivants :</h4>
<div>
  <ul>
    <?php foreach ($groups as $group): ?>
      <li><a>
        <?= $group->name ?></a></li>
    <?php endforeach;  ?>
  </ul>
</div>
<h4>Est administrateur des espaces protégés suivants :</h4>
<div>
  <ul>
    <?php foreach ($espaces as $ep): ?>
      <li><a href="<?= site_url('espace/fiche_espace/'. $ep->id) ?>">
        <?= $ep->nom ?></a></li>
    <?php endforeach;  ?>
  </ul>
</div>
