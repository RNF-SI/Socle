<h2>Affleurement <?= $affl->nom ?></h2>
<div id="map"></div>
<div>
  <h4>Description :</h4>
  <?= $affl->description ?>
</div>
<script>
  var geom = <?= $affl->geom ?>;
</script>
