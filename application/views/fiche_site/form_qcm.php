<?php
// Formulaire de renseignements et pointage carto pour les éléments de QCM
$interets = [
  'scientifique' => ['scientifique', 'flask'],
  'pedagogique' => ['pédagogique', 'chalkboard-teacher'],
  'esthetique' => ['esthétique', 'image'],
  'historique' => ['historique / culturel', 'book'],
];

function checkbox ($name, $label, $icon) {
  return '<div class="checkbox"><label><input type="checkbox" class="item-control" name="interet_'
    . $name . '" disabled /> <span class="fas fa-'
    . $icon . '"> </span> ' . $label . '</label></div>';
}
?>
<div class="modal remarquable-dialog" role="dialog" id="complements-dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Informations complémentaires</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

        <div id="dialog-map-wrapper" class="card">
          <div class="card-header bg-primary text-white">
            <a class="card-link text-white" data-toggle="collapse" href="#collapseMap">
              <span class="fas fa-map-marked"> </span> Cartographier l'élément
            </a>
          </div>
          <div class="collapse hide" id="collapseMap">
            <div class="card-body">
              <p>Vous pouvez indiquer sur la carte l'élément en choisissant un des outils à gauche. Vous pouvez au choix
                cartographier sous forme de point, de ligne, de polygone ou de cercle.</p>
              <div id="dialog-map"></div>
            </div>
          </div>
        </div>

        <form class="form-horizontal">
          <div class="checkbox">
            <label>
              <input type="checkbox" class="item-control" name="remarquable" />
              &starf; Cet élément est remarquable
            </label>
          </div>
          <div id="dialog-remarquable-infos" class="inactive">
            <p>Cet élément est intéressant d'un point de vue :</p>
            <?php foreach ($interets as $i=>$d) {
              print checkbox($i, $d[0], $d[1]);
            } ?>
          </div>
          <br />
          <div class="form-group">
            <label>Commentaires :</label><textarea name="remarquable_info" class="form-control item-control"></textarea>
          </div>
          <input type="hidden" name="geom" class="item-control" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="button-ok" class="btn btn-primary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>