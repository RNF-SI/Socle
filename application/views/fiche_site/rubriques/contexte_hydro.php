<div class="rubrique-description">
    <div class="thumbnail illustration">
      <img src="<?=base_url("resources/images/illustrations/img2.jpg") ?>">
      <div class="caption">Réserve naturelle de Val de Loire (Cher/Nièvre)<br />
        © Nicolas Pointecouteau</div>
    </div>
    <p>Ce contexte recoupe l’ensemble des structures géographiques et des aspects
paysagers liés à la présence de l’eau : l’eau qui coule et l’eau qui stagne, ainsi que
les morphologies qui en sont directement héritées, en particulier celles dues
aux variations de régime et d’activité des cours d’eau, autant de milieux naturels
propices au développement d’environnements riches et parfois spécifiques. De
nombreux domaines lagunaires et plans d’eau sont d’origine anthropique. Cette
première approche est très générale et sera précisée ultérieurement avec les
différents questionnements Q-2<br />

Il est important de spécifier dans quel bassin hydrographique la réserve se situe&nbsp;:
<ul>
<li>Bassin hydrographique rapproché : Bassin de petit ou grand cours d’eau le plus
  proche auquel le territoire de la
réserve appartient directement. Ce peut même être un très petit cours d’eau –
ruisseau ou ru – voire temporaire.</li>
<li>Grand bassin hydrographique général&nbsp;:
Réseau hydrographique général – rivière importante ou fleuve – dans lequel se
situe le territoire de la réserve.</li>
</ul>
</p>
  </div>

  <p>
    Le territoire se situe dans les bassin hydrographiques :
    <ul>
      <li>général : <?=  $site->bassin_hydro_general ?></li>
      <li>raproché : <?= $site->bassin_hydro_rapproche ?></li>
    </ul>
  </p>
  <p> Sur le territoire, on peut observer les éléments suivants&nbsp;:
    <?= liste_caracteristiques($caracteristiques, 'Q1.2') ?>
    <?= complement($complements, 'Q1.2') ?>
    <?= commentaire($commentaire->commentaire) ?>
  </p>
