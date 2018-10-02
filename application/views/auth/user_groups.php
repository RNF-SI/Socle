<div id="user_groups" data-user-id="<?= $userid ?>">
  <ul>
    <?php foreach ($user_groups as $grp): ?>
      <li><?= $grp->name ?> <a href="#" class="remove_group" data-group-id="<?= $grp->id ?>">X</a></li>
    <?php endforeach; ?>
  </ul>
  <div>Ajouter : <select id="groups-add">
    <?php foreach ($groups as $grp): ?>
      <option value="<?= $grp->id ?>"><?= $grp->name ?></option>
    <?php endforeach; ?>
  </select>
  </div>
</div>
