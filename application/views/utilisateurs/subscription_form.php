<div class="container">
  <h2>Formulaire d'inscription</h2>
  <p style="font-style:italic;">* Informations requises</p>
  <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
  <form class="form-horizontal" action="<?= site_url('utilisateurs/subscribe') ?>" method="POST">
    <?= form_input('nom', 'Nom *', set_value('nom')) ?>
    <?= form_input('prenom', 'Prénom *', set_value('prenom')) ?>
    <?= form_input('email', 'Adresse mail *', set_value('email')) ?>
    <?= form_password('password', 'Mot de passe *', set_value('password'))
      .form_password('pwd_confirm', 'Confirmez votre mot de passe *') ?>
    <?= form_input('employeur', 'Organisme', set_value('employeur')) ?>
    <?= form_input('telephone', 'Téléphone', set_value('telephone')) ?>
    <?= form_text('message', 'Sur quel(s) espace(s) naturel(s) souhaitez-vous saisir des données ? *', set_value('message')) ?>
    <div class="text-center"><?= form_submit() ?></div>
  </form>
</div>
