<div class="rubrique panel panel-default" id="<?= $id_rubrique ?>">
  <div class="panel-heading">
    <h3><a data-toggle="collapse" data-parent="#rubriques" href="#collapse-<?=$id_rubrique ?>"><?= $titre ?></a></h3>
  </div>
  <div id="collapse-<?=$id_rubrique ?>" class="panel-collapse collapse">
    <div class="panel-body">
      <button class="btn btn-default button-edit-form"><span class="glyphicon glyphicon-edit"></span></button>
      <div class="message">
        <?php if (function_exists('validation_errors')) echo validation_errors('<div class="alert alert-warning">', '</div>'); ?>
      </div>
      <div class="rubrique-content">
        <p>Chargement du contenu...</p>
      </div>
    </div>
  </div>
</div>
