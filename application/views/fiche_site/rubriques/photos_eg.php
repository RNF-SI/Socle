<div class="row">
  <?php foreach($photos as $phot): ?>
    <div class="col-md-4 photo-thumbnail">
      <div class="thumbnail">
        <?php if ($editable): ?>
          <div class="photo-remove-button" data-photo_id="<?= $phot->id ?>">
            <span class="fas fa-times" title="Supprimer"> </span>
          </div>
        <?php endif; ?>
        <a href="<?= base_url('photos/' . $phot->url) ?>">
          <img src="<?= $this->image_lib->thumbnail_url($phot->url, 300, $phot->mimetype) ?>" class="photo-gallerie" />
        </a>
        <div class="caption"><?= $phot->description ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
