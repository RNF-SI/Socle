<p>Bienvenue sur la base de données géologique de Réserves Naturelles de France&nbsp;!</p>
<div class="alert alert-warning">
  <strong>Attention</strong>
  <p>Ce site est actuellement en construction. Certaines données présentes ne sont pas réelles, elles ne sont rentrées qu'à
    titre de test.</p>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-5">
      <img class="home-image" src="<?= $this->image_lib->thumbnail_url($photo->url, 500) ?>" />
      <div class="home-image-caption">
        <?= $photo->description ?> (<a href="<?= site_url('site/fiche_site/'.$photo->id_site) ?>"><?=$photo->nom_site ?></a>)
      </div>
    </div>
    <div class="col-md-7">
      <div id="map"></div>
    </div>
  </div>
  <div class="row">
    <h3><a href="<?=site_url('espace/liste_espaces') ?>">Explorer...</a></h3>
  </div>
  <div class="row">
    <?php foreach ($espaces as $ep): ?>
      <div class="col-sm-3 home-gallerie-item card">
        <div class="card-body">
        <a href="<?= site_url('espace/fiche_espace/'.$ep->espace_id) ?>">
          <div>
          <img class="card-img-top" src="<?= $this->image_lib->thumbnail_url($ep->url, 200) ?>" />
          <div class="home-image-caption"><?= $ep->nom_espace ?></div>
        </div>
        </a>
      </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
