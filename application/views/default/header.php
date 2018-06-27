<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SOCLE <?= isset($title) ? ' - '.$title : '' ?></title>
        <link rel="icon" type="image/x-icon" href="<?= base_url('resources/images/icone_RNF.png') ?>" />

        <link rel="stylesheet" href="<?= base_url('resources/lib/bootstrap/css/bootstrap4-cerulean.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('resources/lib/leaflet/leaflet.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('resources/lib/leaflet/easy-button.css') ?>" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
   <?php
       if (isset($styles)):
          foreach ($styles as $style): ?>
         <link rel="stylesheet" href="<?php echo substr($style, 0, 4) == 'http' ? $style :  base_url("resources/" . $style) ?>" />
       <?php endforeach;
     endif; ?>
     <link rel="stylesheet" href="<?php echo base_url("resources/css/common.css") ?>" />
        <script src="<?= base_url('resources/lib/jquery-3.2.1.min.js') ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="<?= base_url('resources/lib/leaflet/leaflet.js') ?>"></script>
        <script src="<?= base_url('resources/lib/leaflet/easy-button.js') ?>"></script>
        <script>var base_url = '<?= base_url() ?>';</script>
        <script src="<?= base_url("resources/js/common.js") ?>"></script>
        <?php
        if (isset($scripts)):
           foreach ($scripts as $script): ?>
          <script src="<?php echo substr($script, 0, 4) == 'http' ? $script : base_url("resources/" . $script) ?>"></script>
        <?php endforeach;
      endif; ?>
    </head>
    <body>
        <header>
          <div>
            <div id="logo">
              <img src="<?= base_url('resources/images/logo1.png') ?>" />
            </div>
            <div id="cont-title-nav" class="col-md-12">
              <div class="block-title">
                <h1>SOCLE</h1>
                <span class="subtitle">Regards sur la géodiversité des espaces naturels</span>
              </div>
              <nav class="navbar navbar-expand-md navbar-light bg-light">
               <div class="container-fluid">
                 <ul class="nav navbar-nav">
                   <li class="active nav-item"><a class="nav-link" href="<?= site_url() ?>">Accueil</a></li>
                   <li class="nav-item"><a class="nav-link" href="<?= site_url('espace/liste_espaces') ?>">Explorer</a></li>
                   <li class="nav-item"><a class="nav-link" href="<?= site_url('accueil/aide') ?>">Aide</a></li>
                   <li class="nav-item"><a class="nav-link" href="<?= site_url('accueil/liens') ?>">Liens</a></li>
                 </ul>
                 <ul class="nav navbar-nav navbar-right">
                   <?php
                   $user = $this->auth->user()->row();
                   if (is_null($user)): ?>
                     <li class="nav-item"><a class="nav-link" href="<?= site_url('utilisateurs/subscribe') ?>"><span class="fas fa-user"></span> S'inscrire</a></li>
                     <li class="nav-item"><a class="nav-link" href="#" id="login-link"><span class="fas fa-sign-in-alt"></span> Se connecter</a></li>
                   <?php else: ?>
                     <li class="nav-item"><a class="nav-link" href="<?= site_url('utilisateurs/utilisateur/' . $user->id ) ?>"><span class="fas fa-user"></span> <?=$user->username ?></a></li>
                     <li class="nav-item"><a class="nav-link" href="#" id="logout-link"><span class="fas fa-sign-out-alt"></span> Se déconnecter</a></li>
                   <?php endif;
                   if ($this->auth->is_admin()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('utilisateurs/gestion') ?>"><span class="fas fa-users"></span> Utilisateurs</a></li>
                  <?php endif; ?>
                 </ul>
               </div>
             </nav>
            </div>
          </div>
        </header>
        <div class="container-fluid">

          <div class="modal" id="carto-full">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Carte</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="breadcrumb" id="navigation">
                <a class="breadcrumb-item" href="<?= site_url() ?>">Accueil</a>
                <?php
                if (isset($path)) {
                  for ($i=0; $i<count($path); $i++) {
                    if ($i+1 == count($path)) {
                      echo '<span clas="breadcrumb-item">' . $path[$i]['title'] . '</span>';
                    } else {
                      echo '<a class="breadcrumb-item" href="'. site_url($path[$i]['path']) . '">' . $path[$i]['title'] . '</a>';
                    }
                  }
                }
                ?>
              </div>
            </div>
          </div>
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
