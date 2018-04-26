<div class="container">
  <h4>Photos pour le site</h4>
<p>Utilisez ce formulaire pour enregistrer une photo emblématique de la géologie du site.</p>
<?php
  echo form_open_multipart('site/ajout_photo/' . $ep->id);
  echo form_hidden("site_id", $ep->id);
 ?>
<div class="form-group">
  <input type="file" name="photo" class="form-control" />
</div>
<?= form_text('description', 'Légende de la photo :') ?>
<?= form_submit() ?>
</form>
</div>
