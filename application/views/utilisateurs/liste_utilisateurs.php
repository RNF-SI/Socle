<h2>Gestion des utilisateurs</h2>
<p><a href="<?= site_url('utilisateurs/creation') ?>" class="btn btn-primary">Créer un utilisateur</a></p>
<div id="modal-groups" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Groupes</h4>
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
      <th>Nom</th>
      <th>Email</th>
      <th>Groupes</th>
      <th>Actif</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr data-user-id="<?= $user->id ?>">
      <td><?= $user->username ?></td>
      <td><?= $user->email ?></td>
      <td><a href="#" class="action-groups">Afficher/modifier</a></td>
      <td class="col-active"><?= $user->active ? 'oui' : 'non' ?></td>
      <td><a href="#" class="action-activate"><?= $user->active ? 'désactiver' : 'activer' ?></a></td>
    </tr>
  <?php endforeach; ?>
</tbody>
</table>
