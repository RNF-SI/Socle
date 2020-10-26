<h2>Gestion des utilisateurs</h2>
<p>
  <a href="<?= site_url('utilisateurs/creation') ?>" class="btn btn-primary">Créer un nouvel utilisateur</a>
</p>
<div id="modal-groups" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Groupes</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<table class="table">
  <thead>
    <tr>
      <th></th>
      <th>Prénom</th>
      <th>Nom</th>
      <th>Email</th>
      <th>Organisme</th>
      <th>Téléphone</th>
      <th>Groupes</th>
      <th style="text-align:center;">Statut</th>
      <th style="text-align:center;">Supprimer</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr data-user-id="<?= $user->id ?>">
      <td style="text-align:center;"><a href="<?= site_url('utilisateurs/utilisateur/' . $user->id) ?>"><span class="fas fa-user"></span></a></td>
      <td><a href="<?= site_url('utilisateurs/utilisateur/' . $user->id) ?>"><?= $user->first_name ?></a></td>
      <td><a href="<?= site_url('utilisateurs/utilisateur/' . $user->id) ?>"><?= $user->last_name ?></a></td>
      <td><?= $user->email ?></td>
      <td><?= $user->company ?></td>
      <td><?= $user->phone ?></td>
      <td><a href="#" class="action-groups">Afficher / Modifier</a></td>
      <td class="col-active" style="text-align:center;"><a href="#" class="action-activate"><?= $user->active ? '<span title="Actif (désactiver)" class="fas fa-toggle-on"></span>' : '<span title="Inactif (activer)" class="fas fa-toggle-off"></span>' ?></a></td>
      <td style="text-align:center;"><a href="#" class="action-delete-user" title="Supprimer"><span class="fas fa-trash"></span></a></td>
    </tr>
  <?php endforeach; ?>
</tbody>
</table>
<br>
<h2>Gestion des groupes</h2>
<p>
  <a href="<?= site_url('utilisateurs/creation_groupe') ?>" class="btn btn-primary">Créer un nouveau groupe</a>
</p>
<table class="table">
  <thead>
    <tr>
      <th>Nom du groupe</th>
      <th>Description</th>
      <th style="text-align:center;">Supprimer</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($groups as $group): ?>
    <tr data-group-id="<?= $group->id ?>">
      <td><?= $group->name ?></td>
      <td><?= $group->description ?></td>
      <td style="text-align:center;"><a href="#" class="action-delete-group" title="Supprimer"><span class="fas fa-trash"></span></a></td>
    </tr>
  <?php endforeach; ?>
</tbody>
</table>
