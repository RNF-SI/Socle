<h2>Ajout de groupe</h2>
<form method="POST" action="<?= site_url('utilisateurs/creation_groupe') ?>">
  <?= form_input('name', 'Nom du groupe :') ?>
  <?= form_input('description', 'Description :') ?>
  <?= form_submit() ?>
</form>
