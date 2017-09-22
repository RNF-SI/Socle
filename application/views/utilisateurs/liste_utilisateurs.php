<h2>Gestion des utilisateurs</h2>
<table class="table">
  <thead>
    <tr>
      <th>Nom</th>
      <th>Email</th>
      <th>Actif</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><?= $user->username ?></td>
      <td><?= $user->email ?></td>
      <td><?= $user->active ? 'oui' : 'non' ?></td>
      <td><a href="<?= site_url('utilisateurs/modif_utilisateur/' . $user->id) ?>">modifier</a></td>
    </tr>
  <?php endforeach; ?>
</tbody>
</table>
