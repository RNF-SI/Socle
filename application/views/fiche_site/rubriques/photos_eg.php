<div class="card-columns">
  <?php foreach($photos as $phot): ?>
    <div class="card w-75 photo-thumbnail">
      <div class="card-header">
        <?php if ($editable): ?>
          <div class="photo-remove-button" data-photo_id="<?= $phot->id ?>">
            <span class="fas fa-times" title="Supprimer"> </span>
          </div>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <a href="<?= base_url('photos/' . $phot->url) ?>">
          <img src="<?= $this->image_lib->thumbnail_url($phot->url, 300, $phot->mimetype) ?>" class="photo-gallerie card-img-top" />
        </a>
        <p class="card-text"><?= $phot->description ?></p>
      </div>
    </div>
  <?php endforeach; ?>
</div>
