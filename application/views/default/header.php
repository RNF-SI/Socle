<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>RNF - Géologie <?= isset($title) ? ' - '.$title : '' ?></title>
        <link rel="icon" type="image/x-icon" href="<?= base_url('resources/images/icone_RNF.png') ?>" />

        <link rel="stylesheet" href="<?= base_url('resources/lib/bootstrap/css/bootstrap.min.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('resources/lib/leaflet/leaflet.css') ?>" />
   <?php
       if (isset($styles)):
          foreach ($styles as $style): ?>
         <link rel="stylesheet" href="<?php echo substr($style, 0, 4) == 'http' ? $style :  base_url("resources/" . $style) ?>" />
       <?php endforeach;
     endif; ?>
     <link rel="stylesheet" href="<?php echo base_url("resources/css/common.css") ?>" />
        <script src="<?= base_url('resources/lib/jquery-3.2.1.min.js') ?>"></script>
        <script src="<?= base_url('resources/lib/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script src="<?= base_url('resources/lib/leaflet/leaflet.js') ?>"></script>
        <script>var base_url = '<?= base_url() ?>';</script>

        <script src="<?php echo base_url("resources/js/common.js") ?>"></script>
        <?php
        if (isset($scripts)):
           foreach ($scripts as $script): ?>
          <script src="<?php echo substr($script, 0, 4) == 'http' ? $script : base_url("resources/" . $script) ?>"></script>
        <?php endforeach;
      endif; ?>
    </head>
    <body>
        <header>
          <div class="row">
            <div class="col-sm-2" id="logo">
              <img src="<?= base_url('resources/images/logo1.png') ?>" />
            </div>
            <div class="col-sm-8"><h1>Base de données géologiques</h1>
              <span class="subtitle">Réserves Naturelles de France</span>
            </div>
            <div class="col-sm-2">
              <div id="user-info">
                <?php
                $user = $this->auth->user()->row();
                if (is_null($user)): ?>
                <a href="#" id="login-link">s'identifier</a>
                <?php else: ?>
                  connecté en tant que <a href="<?= site_url('utilisateurs/utilisateur/' . $user->id ) ?>">
                    <?= $user->username ?></a> / <a href="#" id="logout-link">déconnecter</a>
                <?php endif;
                  if ($this->auth->is_admin()):
                ?>
                <a href="<?= site_url('utilisateurs/gestion') ?>">Gestion des utilisateurs</a>
              <?php endif; ?>

              </div>
            </div>
          </div>

        </header>
        <div class="container">
          <a href="<?= site_url() ?>">Accueil</a>
          <div id="messages-global">
            <?php // gestion des messages
            if (isset($message)) { // message dans les données
              echo '<div class="alert alert-' . (isset($message_class) ? $message_class : 'info');
              echo '" >' . $message . '</div>';
            }
            if ($this->session->flashdata('message')) { // message en session
              echo '<div class="alert alert-' . ($this->session->flashdata('message-class') ? $this->session->flashdata('message-class') : 'info');
              echo '" >' . $this->session->flashdata('message') . '</div>';
            }
            ?>
          </div>
