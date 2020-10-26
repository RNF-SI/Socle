<h2>Création d'utiliisateur</h2>
<?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
<?= isset($message) ? '<div class="alert alert-danger">' . $message . '</div>' : '' ?>
<form method="POST">
  <div class="form-group">
    <label for="username">Nom :</label>
    <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name') ?>" />
  </div>
  <div class="form-group">
    <label for="username">Prénom :</label>
    <input type="text" class="form-control" id="firstname" name="firstname" value="<?= set_value('firstname') ?>" />
  </div>
  <div class="form-group">
    <label for="email">Adresse email :</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>" />
  </div>
  <div class="form-group">
    <label for="password">Mot de passe :</label>
    <input type="password" class="form-control" id="password" name="password" />
  </div>
  <div class="form-group">
    <label for="password_valid">Mot de passe :</label>
    <input type="password" class="form-control" id="password_valid" name="password_valid" />
  </div>
  <div class="form-group">
    <label for="company">Organisme :</label>
    <input type="text" class="form-control" id="company" name="company" value="<?= set_value('company') ?>" />
  </div>
  <div class="form-group">
    <label for="company">Téléphone :</label>
    <input type="text" class="form-control" id="phone" name="phone" value="<?= set_value('phone') ?>" />
  </div>
  <h3>Privilèges</h3>
  <div class="radio">
   <label><input type="radio" name="privilege" value="1" />Administrateur</label>
  </div>
  <div class="radio">
   <label><input type="radio" name="privilege" value="3" />Super-utilisateur</label>
  </div>
  <div class="radio">
   <label><input type="radio" name="privilege" value="2" checked />Utilisateur</label>
  </div>
  <div class="form-group">
 <label for="sel1">Groupes</label>
 <select class="form-control" id="groups" name="groups" multiple>
   <?php foreach ($groups as $id => $val): ?>
     <option value="<?= $id ?>"><?= $val ?></option>
   <?php endforeach; ?>
 </select>
</div>
  <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>
