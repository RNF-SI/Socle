<h4>Photos du site</h4>
<div class="row">
  <?php foreach($photos as $phot): ?>
    <div class="col-md-4 photo-thumbnail">
      <div class="thumbnail">
        <?php if ($editable): ?>
          <div class="photo-remove-button" data-photo_id="<?= $phot->id ?>">
            <span class="glyphicon glyphicon-remove" title="Supprimer"> </span>
          </div>
        <?php endif; ?>
        <a href="<?= base_url('photos/' . $phot->url) ?>">
          <img src="<?= $this->image_lib->thumbnail_url($phot->url, 300) ?>" class="photo-gallerie" />
        </a>
        <div class="caption"><?= $phot->description ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
