<h4>Photos du site</h4>
<div class="row">
  <?php foreach($photos as $phot): ?>
    <div class="col-md-4">
      <div class="thumbnail">
        <a href="<?= base_url('photos/' . $phot->url) ?>">
          <img src="<?= base_url('photos/' . $phot->url) ?>" class="photo-gallerie" />
        </a>
        <div class="caption"><?= $phot->description ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
