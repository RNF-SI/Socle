<div class="container">
  <h2>Formulaire d'inscription</h2>
  <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
  <form class="form-horizontal" action="<?= site_url('utilisateurs/subscribe') ?>" method="POST">
    <?= form_input('nom', 'nom', set_value('nom')) ?>
    <?= form_input('email', 'adresse mail', set_value('email')) ?>
    <?= form_password('password', 'mot de passe', set_value('password'))
      .form_password('pwd_confirm', 'confirmez votre mot de passe') ?>
    <?= form_text('message', 'Sur quel espace naturel souhaitez-vous saisir des données ?', set_value('message')) ?>
    <div class="text-center"><?= form_submit() ?></div>
  </form>
</div>
