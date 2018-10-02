<div class="rubrique-description">
    <p>Cette première approche recouvre les paysages que l’on peut rencontrer dans
les régions continentales y compris leurs bordures littorales. Les variations d’altitude
entre le point le plus haut et le point le plus bas d’un territoire sont aussi
des raisons d’y observer des morphologies ou des paysages généraux parfois très
différents, voire souterrains.<br />
Un territoire peut à la fois se situer en moyenne montagne, dans un paysage
vallonné, occuper un fond de vallée, dans un contexte volcanique, avec une
morphologie glaciaire héritée, le tout marqué par la présence de nombreux affleurements
rocheux, sans compter quelques marques anthropiques.<br />
Cette première approche est très générale. Les différents aspects seront précisés
ultérieurement avec l’ensemble des questionnements Q2.</p>
  </div>
  <!-- <p>Surface couverte par la réserve : <?= round($site->surface/1e4, 1) ?>&nbsp;Ha <br />
   Altitude du point le plus haut :  $site->altitude_max_ep &nbsp;m <br />
  Altitude du point le plus bas :  /* $site->altitude_min_ep */&nbsp;m</p>
  <p><?php if ($site->nombre_morcellement == 0): ?>Territoire d'un seul tenant
  <?php else: ?>Territoire constitué de <?= $site->nombre_morcellement; ?> parties disjointes<?php endif; ?></p> -->
  <p>Le territoire est situé en :
    <?= liste_caracteristiques($caracteristiques, 'Q1.1') ?>
  </p>
  <p>Le territoire montre les éléments suivants :
    <?= liste_caracteristiques($caracteristiques, 'Q1.1.2') ?>
    <?= complement($complements, 'Q1.1.2') ?>
    <?= commentaire($commentaire->commentaire) ?>
  </p>
