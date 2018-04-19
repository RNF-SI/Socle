<div class="eg-head">
  <h2><?= isset($eg) ? $eg->intitule : 'Nouvelle entité géologique' ?></h2>
  <p>pour le site <em><a href="<?= site_url('site/fiche_site/'.$site->id) ?>"><?= $site->nom ?></a></em></p>
</div>
<form class="form-horizontal" action="<?= site_url('site/ajout_eg/'  .$site->id . ($id_eg ? '/' . $id_eg : '')) ?>" method="POST">
  <div class="row">
    <div class="col-sm-7">
      <p>Cliquez sur la carte pour localiser le point et remplir automatiquement les références de l'entité cartographiée.
        (ne fonctionne que sur la France métropolitaine)</p>
      <p>Pour une entité fractionnée, plusieurs points peuvent être positionnés.</p>
        <?php
        function set_value_obj($label, $obj, $html_escape=TRUE) {
          return set_value($label, isset($obj->$label) ? $obj->$label : NULL, $html_escape);
        }

        echo form_hidden('geom', set_value_obj('geojson', $eg, FALSE));
        echo form_input('intitule', 'Nom de l\'entité (libre)', set_value_obj('intitule', $eg));
        echo form_input('code', 'code de l\'entité sur la carte géologique', set_value_obj('code', $eg));
        echo form_input('intitule', 'nom de l\'entité sur la légende de la carte', set_value_obj('intitule', $eg));
        ?>
    </div>
    <div class="col-sm-5">
      <div id="map" style="height:400px;"></div>
    </div>
  </div>

  <h3>Âge des roches</h3>
  <ol id="echelle_geol" data-name="id_ere_geol" data-value="<?= set_value_obj('id_ere_geol',  $eg) ?>">
  <?php
  // structure arborescente de l'échelle géologique
  function makeTree($elt) {
    echo '<li data-value="'. $elt->id . '">' . $elt->label;
    if (!empty($elt->children)) {
      echo '<ol>';
      foreach ($elt->children as $child) {
        makeTree($child);
      }
      echo '</ol>';
    }
    echo '</li>';
  }

  foreach ($echelle_geol as $top) {
    makeTree($top);
  }
   ?>
 </ol>

<h3>Aspect des affleurements</h3>
 <?= form_select('quantite_affleurements', 'Présence d\'affleurements :', [
   'aucun' => 'aucun affleurement',
   'un seul' => 'un seul',
   'nombreux' => 'nombreux affleurements',
   'surfaces' => 'grandes surfaces d\'affleurements'
 ], set_value_obj('quantite_affleurements', $eg)) ?>
<?= form_checkbox('affleurements_accessibles', 'Affleurements accessibles', set_value_obj('affleurements_accessibles', $eg)) ?>
<div id="affleurements" class="jumbotron">
Liste des affleurements <br />
  [ A FAIRE ]
</div>

<h3>Informations complémentaires</h3>
<p>Pour cette entité géologique : rechercher et décrire les quelques informations
complémentaires importantes à connaître :<ul>
<li>décrites sur la notice associée à la carte (synthèse de la notice)</li>
<li>révélées par d’autres sources (spécialiste, observations de terrain, autres
documentations, etc.)</li>
<li>Informations complémentaires, etc.</li>
</ul></p>
<?= form_text('complements', 'informations complémentaires', set_value_obj('complements', $eg)) ?>

 <?= form_submit() ?>
</form>
<script>
  var site = <?= json_encode($site) ?>;
</script>
