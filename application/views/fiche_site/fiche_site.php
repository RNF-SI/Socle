<script>
  var site = <?= json_encode($site) ?>;
  var entite_id = <?= $site->id ?>;
  var type_rubrique = 'Site';
</script>
<div class="container-fluid">
<div class="row">
<div class="col-sm-2" id="col-menu">
  <div id="col-menu-content" class="panel panel-default"  data-spy="affix" data-offset-top="200">
    <div class="panel-body">
  <div id="main_image">
    <?php if (count($site->photos) > 0): ?>
      <img src="<?= $this->image_lib->thumbnail_url($site->photos[0]->url, 200) ?>" class="img-rounded" />
    <?php elseif ($editable): ?>
      <div id="alert-image" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Site non illustré</h4>
            </div>
            <div class="modal-body">
              <p>Si vous n'ajoutez pas au moins une image représentative du site, il n'aparaîtra pas en page d'accueil.</p>
              <p>Pour ajouter une image, ouvrez le panneau "<a href="#points_de_vue">Images / documents</a>"
                et cliquez sur "éditer".</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Compris</button>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <div id="carto">
    <div id="map-main" class="minimap"></div>

  </div>
  <div class="" id="tdm">
      <ul>
        <li><a href="#Q-1">Q-1 / Approche géographique du territoire</a>
          <ul>
            <li><a href="#points_de_vue">Q-1 /0 Images et documents</a></li>
            <!--<li><a href="#contexte_general">Q-1 /1 Contexte général</a></li>-->
            <li><a href="#contexte_hydro">Q-1 /2 Contexte hydrographique général</a></li>
            <li><a href="#contexte_littoral">Q-1 /3 Contexte général littoral et marin</a></li>
            <li><a href="#contexte_anthropique">Q-1 /4 Contexte anthropique général - Aménagements</a></li>
          </ul>
        </li>
        <li><a href="#Q-2">Q-2 / Aspects morphologiques et structuraux des terrains</a>
          <ul>
            <li><a href="#contexte_geol_regional">Q-2 /0 Contexte géologique régional et local</a></li>
            <li><a href="#structures_geol_regionales">Q-2 /1 Grandes structures géologiques régionales</a></li>
            <li><a href="#structures_geol_territoire">Q2 /2 Structures géologiques à l’échelle du territoire</a></li>
            <li><a href="#contexte_sismique">Q-2 /3 Contexte sismique</a></li>
            <li><a href="#volcanisme">Q-2 /4 Structures et morphologies liées au volcanisme</a></li>
            <li><a href="#morpho_erosive">Q-2 /5 Morphologies liées à l’érosion générale</a></li>
            <li><a href="#morpho_karstique">Q-2 /6 Morphologies karstiques</a></li>
            <li><a href="#morpho_glaciaire">Q-2 /7 Morphologies glaciaires</a></li>
            <li><a href="#morpho_alluvionnaire">Q-2 /8 Morphologies alluvionnaires des cours d’eau</a></li>
            <li><a href="#plages_littorales">Q-2 /9 Plages littorales : sable, galets et vase</a></li>
            <li><a href="#dunes_littorales">Q-2 /10 Systèmes dunaires littoraux</a></li>
            <li><a href="#cotes_rocheuses">Q-2 /11 Côtes rocheuses</a></li>
            <li><a href="#structures_rocheuses_particulieres">Q-2 /12 Structures et figurés rocheux particuliers à petite et moyenne échelle</a></li>
          </ul>
        </li>
        <li><a href="#Q-3">Q-3 / Identification des terrains, des roches et des fossiles</a>
          <ul>
            <li><a href="#infos_preliminaires">Q3 /00 Informations préliminaires</a></li>
            <li><a href="#Q3-1"><b>Q3 /1 Recensement des différentes entités géologiques constituant le sous-sol du territoire</a></b></li>
          </ul>
        </li>
        <li><a href="#Q-4">Q-4 / Objets géologiques remarquables</a></li>
      </ul>
    </div>
</div>
</div>
</div>

<div id="page-content" class="container-fluid col-sm-10">
    <div id="entete">
      <h1><?= $site->nom ?></h1>
    </div>
    <?php if ($site->statut_validation == 'attente' || $site->statut_validation == 'validation'): ?>
      <div class="alert alert-warning"><strong>Attention</strong><br />
        <p>Cet espace est
        <?php if ($site->statut_validation == 'attente') {
          echo 'au stade de brouillon. Cliquez ci-dessous pour le publier.</p>';
          echo '<a href="'. site_url('site/publication/' . $site->id) . '" class="btn btn-success">Publier</a>';
        }
        ?>
      </div>
    <?php endif; ?>
<div class="last_modified">Modifié le <?= date('d/m/Y', strtotime($site->last_modified)) ?>
  par <?= $this->auth->user($site->modified_by_userid)->row()->username ?>.</div>
<p>
  <a href="<?= site_url('site/resume/' . $site->id) ?>">Accès à la fiche synthétique</a>
</p>
<?php if ($editable): ?>
  <div>
    <a href="<?=site_url('site/creation/' . $site->ep_id . '/' . $site->id) ?>" class="btn btn-default">Modifier le site</a>
  </div>
<?php endif; ?>
<div id="rubriques" class="panel-group">
  <h2>Q-1 / Approche géographique du territoire</h2>
  <div class="explication">
    <p>La découverte géologique d’un territoire débute toujours par son approche géographique
et un questionnement très simple pour en décrire l’environnement&nbsp;!
Dans quel contexte général se situe-t-il&nbsp;? Quels sont les principaux types de
paysages et morphologies associés&nbsp;: plaine, plateau, collines et vallons, massif
montagneux, haute montagne glaciaire, réseau hydrographique, zone littorale,
milieu marin, etc.<br />
Il est aussi important dans cette première approche de prendre en compte le
contexte anthropique. Le, les, ou certains sites sont-ils liés aux activités humaines,
telles que d’anciennes carrières par exemple&nbsp;?</p>

<p>Un paysage, pris dans l’acceptation géographique physique du terme, ne doit
rien au hasard. Il répond principalement à une histoire d’ordre géologique.<br />
Un paysage résulte de quelques facteurs principaux&nbsp;:
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
    $this->load->view('fiche_site/base_rubrique', [
      'titre' => 'Q-1 /0 Images et documents',
      'ep' => $site,
      'id_rubrique' => 'points_de_vue']);

    /*$this->load->view('fiche_site/base_rubrique', [
      'titre' => 'Q-1 /1 Contexte général',
      'ep' => $site,
      'id_rubrique' => 'contexte_general']); */

    $this->load->view('fiche_site/base_rubrique', [
      'titre' => 'Q-1 /2 Contexte hydrographique général',
      'ep' => $site,
      'id_rubrique' => 'contexte_hydro']);

    $this->load->view('fiche_site/base_rubrique', [
      'titre' => 'Q-1 /3 Contexte général littoral et marin',
      'ep' => $site,
      'id_rubrique' => 'contexte_littoral']);

    $this->load->view('fiche_site/base_rubrique', [
      'titre' => 'Q-1 /4 Contexte anthropique général - Aménagements',
      'ep' => $site,
      'id_rubrique' => 'contexte_anthropique']);
   ?>

<h2 id="Q-2">Q-2 / Aspects morphologiques et structuraux des terrains</h2>
<div class="explication">
   <p>Ce qui crée, ce qui déforme et ce qui use&nbsp;!</p>
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
   qui caractérisent un territoire&nbsp;:
   <ul>
   <li>par son appartenance à un contexte structural général, d’ordre, le plus souvent
   régional,</li>
   <li>et, par l’observation des morphologies, des structures et des objets géologiques
   qui le caractérisent in situ à différentes échelles.</li>
 </ul></p>
   <p>Les observations et les descriptions font, de ce fait, appel&nbsp;:
     <ul>
   <li>à la disposition des terrains dans le sous-sol et à l’affleurement : géologie
   structurale, tectonique,</li>
   <li>et à la morphologie de surface (formes, sites, objets géologiques…), à la fois
   héritière de la nature des roches, de la tectonique et de l’érosion.</li>
 </ul>
 </p>

   <p>Il est important, quelle que soit la localisation d’un territoire donné, de passer
   en revue toutes les typologies de structures et de morphologies, car beaucoup
   d’entre elles sont à cheval sur différents contextes&nbsp;!<br />
   Dans chaque rubrique les listes typologiques associent structures, morphologies
   et objets, car la hiérarchisation entre ces trois critères d’approche serait difficile à
   faire de façon exacte et non utile au niveau de l’analyse d’un territoire. L’important
   étant d’arriver à préciser l’ensemble de ses particularités, à différentes échelles.</p>
 </div>

<?php
 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /0 Contexte géologique régional et local',
   'ep' => $site,
   'id_rubrique' => 'contexte_geol_regional']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /1 Grandes structures géologiques régionales',
   'ep' => $site,
   'id_rubrique' => 'structures_geol_regionales']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q2 /2 Structures géologiques à l’échelle du territoire',
   'ep' => $site,
   'id_rubrique' => 'structures_geol_territoire']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /3 Contexte sismique',
   'ep' => $site,
   'id_rubrique' => 'contexte_sismique',
    'editable' => FALSE]);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /4 Structures et morphologies liées au volcanisme',
   'ep' => $site,
   'id_rubrique' => 'volcanisme',
   'editable' => TRUE]);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /5 Morphologies liées à l’érosion générale',
   'ep' => $site,
   'id_rubrique' => 'morpho_erosive']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /6 Morphologies karstiques',
   'ep' => $site,
   'id_rubrique' => 'morpho_karstique']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /7 Morphologies glaciaires',
   'ep' => $site,
   'id_rubrique' => 'morpho_glaciaire']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /8 Morphologies alluvionnaires des cours d’eau',
   'ep' => $site,
   'id_rubrique' => 'morpho_alluvionnaire']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /9 Plages littorales&nbsp;: sable, galets et vase',
   'ep' => $site,
   'id_rubrique' => 'plages_littorales']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /10 Systèmes dunaires littoraux',
   'ep' => $site,
   'id_rubrique' => 'dunes_littorales']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /11 Côtes rocheuses',
   'ep' => $site,
   'id_rubrique' => 'cotes_rocheuses']);

 $this->load->view('fiche_site/base_rubrique', [
   'titre' => 'Q-2 /12 Structures et figurés rocheux particuliers à petite et moyenne échelle',
   'ep' => $site,
   'id_rubrique' => 'structures_rocheuses_particulieres']);
?>

<h2 id="Q-3">Q-3 / Identification des terrains, des roches et des fossiles</h2>
<div class="explication">
<p>Après avoir décrypté la géographie, les morphologies et l’aspect structural d’un
territoire, son étude géologique passe par l’identification précise de la nature
des terrains qui en constituent le sous-sol.<br />
C’est cette démarche que vous propose ce chapitre en lien direct avec un principe
de questionnement systématique, type base de données. De façon sans
doute plus importante que pour les démarches précédentes, celle-ci nécessite
différents registres de connaissances croisées&nbsp;: roches, minéraux, fossiles, repères
temporels et carte géologique. Les généralités sur ces thèmes sont abordées
dans la première partie de ce document, sous les titres&nbsp;: «&nbsp;Roches et fossiles&nbsp;»
«&nbsp;Espace et temps&nbsp;: Géologie à la carte&nbsp;».</p>
<p>La démarche proposée consiste principalement dans l’utilisation de la carte
géologique en corrélation avec les observations des terrains : en un mot, ce que
nous dit la carte et comment on l’interprète&nbsp;! L’accompagnement par un spécialiste
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
le sous-sol d’un territoire&nbsp;:</p>

<h4>Lire et décoder la carte géologique.</h4>
<p>Elle informe sur la nature du premier niveau rocheux constituant le soussol
de l’endroit considéré, abstraction faite du sol dans le sens pédologique
du terme.</p>

<h4>Arpenter le territoire&nbsp;! Géologie de terrain&nbsp;!</h4>
<p>Repérer les affleurements et identifier les roches qui les constituent.<br />
Il est vrai que certains territoires ne montrent aucun affleurement&nbsp;!<br />
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
$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Q3 /00 Informations préliminaires',
  'ep' => $site,
  'id_rubrique' => 'infos_preliminaires']);

/*
$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Q3 /1 Recensement des différentes entités géologiques constituant le sous-sol du territoire',
  'ep' => $site,
  'id_rubrique' => 'liste_entites_geol']); */
 ?>
<h3 id="Q3-1">Q3 /1 Recensement des différentes entités géologiques constituant le sous-sol du territoire</h3>
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
<a href="<?= site_url('site/ajout_eg/' . $site->id) ?>" class="btn btn-primary">Ajouter une entité</a>

<?php
$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Q3 /2 Le patrimoine géologique des réserves naturelles conservé hors site',
  'ep' => $site,
  'id_rubrique' => 'collections']);
?>


<h2 id="Q-4">Q-4 / Objets géologiques remarquables</h2>
<p>Pour chaque <em>paysage, structure, site, roche, minéral ou fossile, toute formation
ou entité rocheuse</em>, à une échelle ou à une autre, il est important de se poser la
question de son intérêt spécifique à un titre ou à un autre.<br />
Pensez-vous que tel objet ou telle entité géologique possède un intérêt particulier,
même minime, à titre scientifique, pédagogique, esthétique, historique,
culturel, autre&nbsp;?<br />
En un mot se poser la question et identifier ce qui vous semble remarquable&nbsp;!
<quote>Cet objet géologique vous semble-t-il intéressant ?<br />
Est-il qualifiable d’«&nbsp;objet géologique remarquable&nbsp;»&nbsp;?</quote></p>

<p>Au-delà de la subtilité des qualificatifs, la démarche est importante car elle permet
de pointer et de nommer les particularités et les richesses géologiques d’un
territoire, si besoin de les protéger et de les valoriser. Il est alors nécessaire, après
avoir repéré et identifié un objet, de le décrire et d’en faire <em>valider l’importance&nbsp;!</em></p>

<strong>Faites-vous aider si besoin !</strong>
<p>Comme pour les autres disciplines des sciences de la nature, la chose n’est pas
toujours facile pour une personne non spécialiste. Il est alors nécessaire de faire
appel au <em>géologue</em>.</p>

<p>Repérer les objets géologiques remarquables est aussi fondamental pour les intégrer
à votre <em>plan de gestion</em>&nbsp;:
<ul><li>premièrement dans la phase de diagnostic du territoire pour avoir un état
des lieux le plus complet possible,</li>
<li>et ensuite dans la définition des objectifs de gestion, en termes d’études, de
conservation, de protection et de valorisation auprès de différents publics.</li></ul>

<p>Parmi les objets géologiques, certains, par leur richesse spécifique ou leur rareté,
ont une valeur exceptionnelle. Ils entrent alors dans le domaine du patrimoine
géologique, à connaitre et impérativement conserver et protéger. Un
<a href="https://inpn.mnhn.fr/programme/patrimoine-geologique/presentation">Inventaire
National du Patrimoine Géologique (INPG)</a> est aujourd’hui en cours. Savoir si
votre territoire est concerné par cet inventaire est aussi chose importante ! Pour
en savoir plus et connaitre le patrimoine identifié dans votre région, n’hésitez
pas à vous rapprocher de votre DREAL.</p>
<p><a class="btn btn-primary" href="https://inpn.mnhn.fr/accueil/recherche-de-donnees/inpg/" target="_blank">Faire une recherche sur l'INPG</a></p>
<?php
$this->load->view('fiche_site/base_rubrique', [
  'titre' => 'Q4 /1 Eléments remarquables identifiés précédemment',
  'site' => $site,
  'editable' => FALSE,
  'id_rubrique' => 'elements_remarquables']);
?>

</div>

</div>
</div>
</div>
