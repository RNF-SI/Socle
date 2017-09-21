<script>
  var espace_protege = <?= json_encode($ep) ?>;
  var entite_id = <?= $ep->id ?>;
  var type_rubrique = 'EP';
</script>
<div id="entete">
  <h1><?= $ep->nom_ep ?></h1>
</div>
<div id="carto">
  <div id="map-main" style="height: 400px;"></div>
</div>
<div id="rubriques" class="panel-group">
  <h2>Q-1 / Approche géographique du territoire</h2>
  <div class="explication">
    <p>La découverte géologique d’un territoire débute toujours par son approche géographique
et un questionnement très simple pour en décrire l’environnement !
Dans quel contexte général se situe-t-il ? Quels sont les principaux types de
paysages et morphologies associés : plaine, plateau, collines et vallons, massif
montagneux, haute montagne glaciaire, réseau hydrographique, zone littorale,
milieu marin, etc.<br />
Il est aussi important dans cette première approche de prendre en compte le
contexte anthropique. Le, les, ou certains sites sont-ils liés aux activités humaines,
telles que d’anciennes carrières par exemple ?</p>

<p>Un paysage, pris dans l’acceptation géographique physique du terme, ne doit
rien au hasard. Il répond principalement à une histoire d’ordre géologique.<br />
Un paysage résulte de quelques facteurs principaux :
<ul>
<li>la nature des roches qui en constituent les sous-sols,</li>
<li>les mouvements tectoniques qui soulèvent, affaissent, cassent, déplacent
et plissent les terrains,</li>
<li>les éruptions volcaniques qui peuvent en rajouter une ou plusieurs
couches,</li>
<li>les phénomènes d’érosion qui usent, sculptent, transportent et déposent,</li>
<li>le couvert végétal qui habille et qui protège</li>
<li>sans parler de l’homme qui pioche, cultive, pelte, bâtit et qui s’installe !</li>
</ul>
</p>
<p>La première approche du territoire Q-1 sera donc celle de la description de
son environnement géographique au travers d’une grille d’analyse caractérisée
par un regard morphologique très généraliste.<br />

Les étapes suivantes de l’analyse Q-2 permettront d’en définir de façon plus
précise les éléments morphologiques et structuraux avec un regard plus géologique,
puis, en Q-3, la nature précise des terrains.<br />

La rubrique Q-4 invite à s’interroger et faire le point sur les éventuelles richesses
spécifiques, objets géologiques remarquables et patrimoniaux de
chaque territoire.</p>
</div>

  <?php
    $this->load->view('fiche_ep/base_rubrique', [
      'titre' => 'Q1 /0 Points de vue / Panoramas',
      'ep' => $ep,
      'id_rubrique' => 'points_de_vue']);

    $this->load->view('fiche_ep/base_rubrique', [
      'titre' => 'Q1 /1 Contexte général',
      'ep' => $ep,
      'id_rubrique' => 'contexte_general']);

    $this->load->view('fiche_ep/base_rubrique', [
      'titre' => 'Q1 /2 Contexte hydrographique général',
      'ep' => $ep,
      'id_rubrique' => 'contexte_hydro']);

    $this->load->view('fiche_ep/base_rubrique', [
      'titre' => 'Q1 /3 Contexte général littoral et marin',
      'ep' => $ep,
      'id_rubrique' => 'contexte_littoral']);

    $this->load->view('fiche_ep/base_rubrique', [
      'titre' => 'Q1 /4 Contexte anthropique général - Aménagements',
      'ep' => $ep,
      'id_rubrique' => 'contexte_anthropique']);
   ?>

<h2>Q-2 / Aspects morphologiques et structuraux des terrains</h2>
<div class="explication">
   <p>Ce qui crée, ce qui déforme et ce qui use !</p>
   <p>Après avoir proposé une première découverte d’un territoire au travers de ses
   grandes caractéristiques géographiques générales, les questionnements suivants
   s’attachent à préciser les aspects géomorphologiques et structuraux des terrains
   concernés.</p>
   <h3>Géomorphologie</h3>
   <p>La géomorphologie est la science qui étudie les formes du relief terrestre à différentes
   échelles. Elle s’appuie sur la nature des roches du substrat, leur disposition
   dans le sous-sol et à l’affleurement, les phénomènes tectoniques qui les ont mis
   en place, l’érosion qui les sculpte au quotidien ainsi que l’observation des dépôts
   de surface (colluvions, alluvions ou dépôts éoliens) plus ou moins importants.</p>
   <h3>Géologie structurale</h3>
   <p>La géologie structurale est la science qui étudie la disposition des terrains les
   uns par rapport aux autres, dans le sous-sol et à l’affleurement, ainsi que les phénomènes
   tectoniques qui les ont déformés et mis en place.</p>

   <p>Ces regards croisés permettent de faire le tour de l’ensemble des morphologies,
   des structures et des objets géologiques que l’on peut rencontrer dans le
   contexte de la France métropolitaine et par-delà les mers. Il exclut les aspects
   très spécifiques de certaines régions du Grand Nord ou des déserts.<br />
   Il permet de passer en revue et de préciser, avec un vocabulaire adapté, les éléments
   qui caractérisent un territoire :
   <ul>
   <li>par son appartenance à un contexte structural général, d’ordre, le plus souvent
   régional,</li>
   <li>et, par l’observation des morphologies, des structures et des objets géologiques
   qui le caractérisent in situ à différentes échelles.</li>
 </ul></p>
   <p>Les observations et les descriptions font, de ce fait, appel :
     <ul>
   <li>à la disposition des terrains dans le sous-sol et à l’affleurement : géologie
   structurale, tectonique,</li>
   <li>et à la morphologie de surface (formes, sites, objets géologiques…), à la fois
   héritière de la nature des roches, de la tectonique et de l’érosion.</li>
 </ul>
 </p>

   <p>Il est important, quelle que soit la localisation d’un territoire donné, de passer
   en revue toutes les typologies de structures et de morphologies, car beaucoup
   d’entre elles sont à cheval sur différents contextes !<br />
   Dans chaque rubrique les listes typologiques associent structures, morphologies
   et objets, car la hiérarchisation entre ces trois critères d’approche serait difficile à
   faire de façon exacte et non utile au niveau de l’analyse d’un territoire. L’important
   étant d’arriver à préciser l’ensemble de ses particularités, à différentes échelles.</p>
 </div>

<?php
 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /0 Contexte géologique régional et local',
   'ep' => $ep,
   'id_rubrique' => 'contexte_geol_regional']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /1 Grandes structures géologiques régionales',
   'ep' => $ep,
   'id_rubrique' => 'structures_geol_regionales']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /2 Structures géologiques à l’échelle du territoire',
   'ep' => $ep,
   'id_rubrique' => 'structures_geol_territoire']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /3 Contexte sismique',
   'ep' => $ep,
   'id_rubrique' => 'contexte_sismique']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /4 Structures et morphologies liées au volcanisme',
   'ep' => $ep,
   'id_rubrique' => 'volcanisme']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /5 Morphologies liées à l’érosion générale',
   'ep' => $ep,
   'id_rubrique' => 'morpho_erosive']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /6 Morphologies karstiques',
   'ep' => $ep,
   'id_rubrique' => 'morpho_karstique']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /7 Morphologies glaciaires',
   'ep' => $ep,
   'id_rubrique' => 'morpho_glaciaire']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /8 Morphologies alluvionnaires des cours d’eau',
   'ep' => $ep,
   'id_rubrique' => 'morpho_alluvionnaire']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /9 Plages littorales&nbsp;: sable, galets et vase',
   'ep' => $ep,
   'id_rubrique' => 'plages_littorales']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /10 Systèmes dunaires littoraux',
   'ep' => $ep,
   'id_rubrique' => 'dunes_littorales']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /11 Côtes rocheuses',
   'ep' => $ep,
   'id_rubrique' => 'cotes_rocheuses']);

 $this->load->view('fiche_ep/base_rubrique', [
   'titre' => 'Q2 /12 Structures et figurés rocheux particuliers à petite et moyenne échelle',
   'ep' => $ep,
   'id_rubrique' => 'structures_rocheuses_particulieres']);
?>

<h2>Q-3 / Identification des terrains, des roches et des fossiles</h2>
<div class="explication">
<p>Après avoir décrypté la géographie, les morphologies et l’aspect structural d’un
territoire, son étude géologique passe par l’identification précise de la nature
des terrains qui en constituent le sous-sol.<br />
C’est cette démarche que vous propose ce chapitre en lien direct avec un principe
de questionnement systématique, type base de données. De façon sans
doute plus importante que pour les démarches précédentes, celle-ci nécessite
différents registres de connaissances croisées : roches, minéraux, fossiles, repères
temporels et carte géologique. Les généralités sur ces thèmes sont abordées
dans la première partie de ce document, sous les titres : « Roches et fossiles »
« Espace et temps : Géologie à la carte ».</p>
<p>La démarche proposée consiste principalement dans l’utilisation de la carte
géologique en corrélation avec les observations des terrains : en un mot, ce que
nous dit la carte et comment on l’interprète ! L’accompagnement par un spécialiste
s’avérera sans doute le plus souvent nécessaire.</p>

<h3>Préambule important !</h3>
<p>Comme expliqué dans le chapitre consacré à la carte géologique, cette dernière
ne décrit que les ensembles rocheux constituant la partie supérieure du sous-sol.
Or de nombreux affleurements – parois, falaises, cavités, tranchées, fronts
de taille d’anciennes carrières ou actuelles – présentent des affleurements verticaux
avec parfois une succession de niveaux rocheux de natures et d’âges différents.
Dans ces cas précis, seul le niveau supérieur est mentionné sur la carte.
Cependant celle-ci peut apporter des renseignements utiles car les couches
sous-jacentes peuvent, suivant les déformations des terrains, affleurer à d’autres
endroits dans le territoire étudié, ou à l’extérieur de celui-ci.</p>

<h3>Des actions à mener de front !</h3>
<p>Méthodologie pour l’identification des différentes unités rocheuses constituant
le sous-sol d’un territoire :</p>

<h4>Lire et décoder la carte géologique.</h4>
<p>Elle informe sur la nature du premier niveau rocheux constituant le soussol
de l’endroit considéré, abstraction faite du sol dans le sens pédologique
du terme.</p>

<h4>Arpenter le territoire ! Géologie de terrain !</h4>
<p>Repérer les affleurements et identifier les roches qui les constituent.<br />
Il est vrai que certains territoires ne montrent aucun affleurement !<br />
De toute façon, la simple observation des affleurements et des échantillons
prélevés sur ces derniers ne suffira pas en général à les identifier précisément.
Elle devra être complétée d’informations complémentaires et si besoin d’une
étude microscopique et physico-chimique en laboratoire.</p>

<h4>Rechercher des informations complémentaires</h4>
<p>Banques de données, livres, guides régionaux, bibliographie dans des parutions
scientifiques traitant de la région, etc.</p>

<h4>Faire appel aux spécialistes</h4>
<p>Ces différentes approches sont à mener conjointement.</p>
</div>
<?php
$this->load->view('fiche_ep/base_rubrique', [
  'titre' => 'Q3 /00 Informations préliminaires',
  'ep' => $ep,
  'id_rubrique' => 'infos_preliminaires']);

/*
$this->load->view('fiche_ep/base_rubrique', [
  'titre' => 'Q3 /1 Recensement des différentes entités géologiques constituant le sous-sol du territoire',
  'ep' => $ep,
  'id_rubrique' => 'liste_entites_geol']); */
 ?>
<h3>Q3 /1 Recensement des différentes entités géologiques constituant le sous-sol du territoire</h3>
<?php if (empty($entites_geol)): ?>
  <p>Aucune entité enregistrée.</p>
<?php else: ?>
  <p>Cliquez sur une entité pour voir le détail.</p>
  <div class="list-group">
    <?php foreach ($entites_geol as $eg) {
      echo '<a href="' . site_url('site/fiche_entite_geol/' . $eg->id) .'" class="list-group-item">' . $eg->intitule . '</a>';
    } ?>
  </div>
<?php endif; ?>
<a href="<?= site_url('site/ajout_eg/' . $ep->id) ?>" class="btn btn-primary">Ajouter une entité</a>

</div>
