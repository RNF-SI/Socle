<?php $modifiable = isset($modifiable) && $modifiable; ?>
<div class="rubrique card" id="<?= $id_rubrique ?>">
  <div class="card-header">
    <a href="#navigation" title="Haut de page" class="link-gotop">&uarr;</a>
    <h3><a data-toggle="collapse" data-parent="#rubriques" href="#collapse-<?=$id_rubrique ?>"><?= $titre ?></a></h3>
  </div>
  <div id="collapse-<?=$id_rubrique ?>" class="rubrique-collapse collapse">
    <div class="card-body">
      <div class="rubrique-toolbar">
        <?php if ($modifiable || (isset($editable) && $editable)): ?>
          <div class="btn-group btn-group-sm">
            <button class="btn btn-default button-edit-form"><span class="fas fa-edit"> </span> Editer</button>
          </div>
      <?php endif; ?>
    </div>
      <div class="message">
        <?php if (function_exists('validation_errors')) echo validation_errors('<div class="alert alert-warning">', '</div>'); ?>
      </div>
      <div class="rubrique-content">
        <p>Chargement du contenu...</p>
      </div>
    </div>
  </div>
</div>
