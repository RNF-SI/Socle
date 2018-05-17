<?php $editable = $this->auth->is_admin(); ?>
<script>
  var id_ep = <?= $ep->id ?>;
</script>
<h1><?= $ep->nom ?></h1>
<div class="container-fluid">
<div id="map"></div>


<?php if ($editable): ?>
  <div><a href="<?= site_url('espace/modification/' . $ep->id) ?>" class="btn btn-primary">Modifier</a></div>
<?php endif; ?>
<?php if ($ep->monosite == 't'):
  $site = $sites[0];
  ?>
  <div class="row">
    <div class="col-sm-6" style="text-align:center;">
      <h3><a href="<?= site_url('site/fiche_site/' . $site->id) ?>">Fiche de terrain</a></h3>
    </div>
    <div class="col-sm-6">
      <h3><a href="<?= site_url('site/resume/' . $site->id) ?>">Synthèse</a></h3>
    </div>
  </div>
<?php else: ?>
  <h3>Les sites suivants ont été définis pour cet espace&nbsp;:</h3>

  <table class="table">
    <?php
    foreach ($sites as $site):
      ?>
      <tr>
        <td><?= $site->nom ?></td>
        <td><a href="<?= site_url('site/fiche_site/' . $site->id) ?>">Fiche de terrain</a></td>
        <td><a href="<?= site_url('site/resume/' . $site->id) ?>">Synthèse</a></td>
        <td><?php if ($editable): ?>
          <a href="<?= site_url('site/modification/'. $site->id . '/' . $ep->id) ?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"> </span></a>
        <?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <?php if ($editable): ?>
    <div><a href="<?= site_url('site/creation/' . $ep->id) ?>" class="btn btn-primary">Ajouter un site</a></div>
<?php endif; endif; ?>

</div>
