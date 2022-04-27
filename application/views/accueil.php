<!-- <div class="alert alert-warning">
  <strong>Attention</strong>
  <p>Ce site est actuellement en construction. Certaines données présentes ne sont pas réelles, elles ne sont rentrées qu'à
    titre de test.</p>
</div> -->
<br>
<div class="container">
  <div class="row">
    <div class="col-md-5 texte_intro">
    Venez saisir et consulter les données géologiques associées aux espaces naturels français. <br><br>Cliquez sur l’un des sites cartographiés pour en découvrir le portrait géologique simplifié. Ou bien inscrivez-vous pour renseigner les informations à propos d’un espace que vous gérez. <br><br>Vous pourrez alors exporter une synthèse de la géodiversité de votre site et votre contribution viendra compléter la base de données compilée à l’échelle du réseau.

    </div>
    <div class="col-md-7">
      <div id="mapprincipale"></div>
    </div>
  </div>
  <!-- <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <?php foreach ($espaces as $ep) : ?>
        <div class="carousel-item active">
          <img class="d-block w-100" src="<?= $this->image_lib->thumbnail_url($ep->url, 500) ?>" alt="First slide">
        </div>
      <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div> -->
  <div class="section" id="carousel">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mr-auto ml-auto">

                    <!-- Carousel Card -->
                    <div class="card card-raised card-carousel">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="3000">
                          <div class="carousel-inner">
                          <?php $count = 0; foreach ($espaces as $ep) : ?>
                            <div class="carousel-item <?php 
                                if($count==0){
                                  echo "active";  
                                }
                                else{
                                    echo " ";
                                }
                            ?>">
                              <img class="d-block w-100" src="<?= $this->image_lib->thumbnail_url($ep->url, 500) ?>"
                              alt="<?= $ep->nom_espace ?>">
                              <div class="carousel-caption d-none d-md-block">
                              <a href="<?= site_url('espace/fiche_espace/' . $ep->espace_id) ?>">
                                <h4>
                                  <i class="fas fa-map-marker-alt"></i>
                                    <?= $ep->nom_espace ?>
                                </h4>
                              </a>
                            </div>
                            </div>
                            <?php $count++; endforeach; ?>
                            <!-- <div class="carousel-item active">
                              <img class="d-block w-100" src="https://rawgit.com/creativetimofficial/material-kit/master/assets/img/bg.jpg"
                              alt="First slide">
                              <div class="carousel-caption d-none d-md-block">
                                <h4>
                                    <i class="material-icons">location_on</i>
                                    Yellowstone National Park, United States
                                </h4>
                              </div>
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="https://rawgit.com/creativetimofficial/material-kit/master/assets/img/bg2.jpg"  alt="Second slide">
                              <div class="carousel-caption d-none d-md-block">
                                <h4>
                                <i class="fas fa-map-marker-alt"></i>
                                    Somewhere Beyond, United States
                                </h4>
                              </div>
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="https://rawgit.com/creativetimofficial/material-kit/master/assets/img/bg3.jpg" alt="Third slide">
                              <div class="carousel-caption d-none d-md-block">
                                <h4>
                                <i class="fas fa-map-marker-alt"></i>
                                    Yellowstone National Park, United States
                                </h4>
                              </div>
                            </div> -->
                          </div>
                          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                          <i class="fas fa-angle-left fa-5x"></i>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                          <i class="fas fa-angle-right fa-5x"></i>
                            <span class="sr-only">Next</span>
                          </a>
                        </div>
                    </div>
                    <!-- End Carousel Card -->

                </div>
            </div>
        </div>
    </div>
</div>