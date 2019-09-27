<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_model extends Entite_abstract_model {
  protected $tableName = 'site';
  protected $qcmLinkTable = 'site_qcm';
  protected $complementTableName = 'complement_site';
  protected $store_user_info = TRUE;
  protected $geometry_format = 'MULTIPOLYGON';


  public function set_nouvelle_version($v) {
    // sert à indiquer qu'on est sur le schéma hiérarchisé
    // (utilisé pour la fonction expérimentale d'arbre de QCM
    // mais à supprimer par la suite)
    if ($v) {
      $this->qcmLinkTable = 'site_ontology';
    }
  }

  public function getByEspace($id_ep) {
    $query = $this->db
      ->get_where($this->tableName, ['ep_id' => $id_ep]);
    return $query->result();
  }

  public function add($data) {
    $id = parent::add($data);

    // Ajout auto des entités géologiques
    $infos = $this->getRockAges($id);
    $this->load->model('entite_geol_model');
    foreach ($infos as $eg) {
      $data_eg = [
        'site_id' => $id,
        'intitule' => $eg->descr,
        'code'=> $eg->notation,
        's_fgeol_id' => $eg->ogc_fid
      ];
      $this->entite_geol_model->add($data_eg);
    }

    return $id;
  }


  // renvoie les éléments remarquables du site et des EG associées
  public function getAllElementsRemarquables($id) {
    $siteElements = $this->db
      ->select('site.id, site.nom, interet_scientifique, interet_pedagogique, interet_esthetique, interet_historique, qcm.id, qcm.label, qcm.rubrique')
      ->join('site_qcm', 'site.id=site_id')
      ->join('qcm', 'qcm.id=qcm_id')
      ->get_where('site', ['remarquable'=>TRUE, 'site.id'=>$id]);
    $res['site'] = $siteElements->result();
    $egElements = $this->db
      ->select('eg.id as eg_id, eg.intitule, interet_scientifique, interet_pedagogique, interet_esthetique, interet_historique, qcm.id, qcm.label, qcm.rubrique')
      ->join('entite_geol_qcm', 'eg.id=entite_geol_id')
      ->join('qcm', 'qcm.id=qcm_id')
      ->get_where('entite_geol as eg', ['remarquable'=>TRUE, 'site_id'=>$id]);
    $res['eg'] = $egElements->result();
    return $res;
  }


  // feuilles des cartes géol associées au site (requete spatiale)
  public function getFeuillesCartes($id_site) {
    $query = $this->db
      ->select(['emprise_cartes_geol.numero', 'emprise_cartes_geol.nom'])
      ->join('emprise_cartes_geol', 'st_intersects(emprise_cartes_geol.geom, site.geom)')
      ->where('site.id', $id_site)
      ->get($this->tableName);
    return $query->result();
  }

  public function getEntitesGeol($id_site) {
    return $this->db->get_where('entite_geol', array('site_id' => $id_site))->result();
  }

  public function getPath($id) {
    $this->db->select(['site.id AS site_id', 'site.nom', 'espace_protege.id AS ep_id',
      'espace_protege.nom as ep_nom', 'monosite'])
      ->from('site')
      ->join('espace_protege', 'ep_id=espace_protege.id')
      ->where(['site.id'=>$id]);
    $res = $this->db->get()->row();
    $data = array();
    if (!$res->monosite) {
      $data[] = ['path'=>'espace/fiche_espace/'.$res->ep_id, 'title'=>$res->ep_nom];
    }
    $data[] = ['path'=>'site/fiche_site/'.$id, 'title'=>$res->nom];
    return $data;
  }

  public function getAllSubelements($id, $with_geom=FALSE) {
    // Séparation en 2 requetes pour alléger les résultats
    $this->db->select('site.nom as site_nom,
      site_qcm.info_complement AS siteqcm_complement,
      site_qcm.remarquable AS siteqcm_remarquable,
      site_qcm.interet_scientifique As siteqcm_interet_scientifique,
      site_qcm.interet_historique As siteqcm_interet_historique,
      site_qcm.interet_pedagogique As siteqcm_interet_pedagogique,
      site_qcm.interet_esthetique As siteqcm_interet_esthetique,
      site_qcm.remarquable_info As siteqcm_remarquable_info,
      qcm_site.id As siteqcm_id_qcm,
      qcm_site.question As siteqcm_question,
      qcm_site.label As siteqcm_label,
      qcm_site.description As siteqcm_description,
      qcm_site.rubrique As siteqcm_rubrique,
      qcm_site.intitule_complement AS siteqcm_intitule_complement,
      photo_site.id AS photo_id,
      photo_site.url as photo_url,
      photo_site.description AS photo_description,
      photo_site.mimetype AS photo_mimetype'
    );
    if ($with_geom) {
      $this->db->select('st_asgeojson(site.geom) AS site_geom');
    }
    $this->db->join('site_qcm', 'site.id=site_qcm.site_id', 'left')
      ->join('qcm AS qcm_site', 'qcm_site.id = site_qcm.qcm_id', 'left')
      ->join('complement_site', 'complement_site.site_id=site.id', 'left')
      ->join('commentaire', 'commentaire.site_id=site.id', 'left')
      ->join('photo AS photo_site', 'site.id=photo_site.site_id', 'left');
    $query = $this->db->get_where('site', ['site.id'=>$id]);

    $data = ['egs'=>[], 'qcms'=>[], 'photos'=>[]];

    foreach ($query->result() as $li) {
      if (!isset($data['nom'])) {
        $data['nom'] = $li->site_nom;
        if ($with_geom) $data['geom'] = $li->site_geom;
      }
      if (!isset($data['qcms'][$li->siteqcm_question][$li->siteqcm_id_qcm]) && !is_null($li->siteqcm_question)) {
        $data['qcms'][$li->siteqcm_question][$li->siteqcm_id_qcm] = ['id'=>$li->siteqcm_id_qcm, 'label'=>$li->siteqcm_label,
        'description'=>$li->siteqcm_description, 'rubrique'=>$li->siteqcm_rubrique,
        'info_complement' => $li->siteqcm_complement, 'intitule_complement' => $li->siteqcm_intitule_complement,
        'remarquable'=>$li->siteqcm_remarquable, 'interet_historique'=>$li->siteqcm_interet_historique, 'interet_scientifique'=>$li->siteqcm_interet_scientifique,
        'interet_pedagogique'=>$li->siteqcm_interet_pedagogique, 'interet_esthetique'=>$li->siteqcm_interet_esthetique, 'remarquable_info'=>$li->siteqcm_remarquable_info];
      }
      if (!isset($data['photos'][$li->photo_id]) && !is_null($li->photo_id)) {
        $data['photos'][$li->photo_id] = ['url'=>$li->photo_url, 'legende'=>$li->photo_description, 'type'=>$li->photo_mimetype];
      }
    }

    $this->db->select('
      eg.id AS eg_id,
      eg.intitule as eg_nom, eg_qcm.info_complement AS egqcm_complement,
      eg_qcm.remarquable AS egqcm_remarquable,
      eg_qcm.interet_scientifique As egqcm_interet_scientifique,
      eg_qcm.interet_historique As egqcm_interet_historique,
      eg_qcm.interet_pedagogique As egqcm_interet_pedagogique,
      eg_qcm.interet_esthetique As egqcm_interet_esthetique,
      eg_qcm.remarquable_info As egqcm_remarquable_info,
      qcm_eg.id As egqcm_id_qcm,
      qcm_eg.question As egqcm_question,
      qcm_eg.label As egqcm_label,
      qcm_eg.description As egqcm_description,
      qcm_eg.rubrique As egqcm_rubrique,
      qcm_eg.intitule_complement AS egqcm_intitule_complement,
      echelle_geol.label AS eg_age_roches,
      affleurement.nom AS affleurement_nom,
      affleurement.id AS affleurement_id,
      affleurement.description AS affleurement_description,
      photo_eg.id AS photo_eg_id,
      photo_eg.url as photo_eg_url,
      photo_eg.description AS photo_eg_description,
      photo_eg.mimetype AS photo_eg_mimetype
    ');
    if ($with_geom) {
      $this->db->select('st_asGeoJson(eg.geom) AS eg_geom,
        st_asGeoJson(affleurement.geom) AS affleurement_geom');
    }
    $this->db
      ->join('entite_geol_qcm AS eg_qcm', 'eg.id=eg_qcm.entite_geol_id', 'left')
      ->join('qcm AS qcm_eg', 'eg_qcm.qcm_id = qcm_eg.id', 'left')
      ->join('echelle_geol', 'ere_geol_id=echelle_geol.id', 'left')
      ->join('affleurement', 'affleurement.eg_id=eg.id', 'left')
      ->join('photo AS photo_eg', 'eg.id=photo_eg.eg_id', 'left');
    $query = $this->db->get_where('entite_geol AS eg', ['eg.site_id'=>$id]);

    foreach ($query->result() as $li) {
      if (!isset($data['egs'][$li->eg_id]) && !is_null($li->eg_id)) {
        $data['egs'][$li->eg_id] = ['nom'=>$li->eg_nom, 'age_roches'=>$li->eg_age_roches, 'qcms'=>[], 'affleurements'=>[], 'photos'=>[]];
        if ($with_geom) $data['egs'][$li->eg_id]['geom'] = $li->eg_geom;
      }
      if (!isset($data['egs'][$li->eg_id]['qcms'][$li->egqcm_question][$li->egqcm_id_qcm]) && !is_null($li->egqcm_question)) {
        $data['egs'][$li->eg_id]['qcms'][$li->egqcm_question][$li->egqcm_id_qcm] = ['id'=>$li->egqcm_id_qcm, 'label'=>$li->egqcm_label,
        'description'=>$li->egqcm_description, 'rubrique'=>$li->egqcm_rubrique,
        'info_complement' => $li->egqcm_complement, 'intitule_complement' => $li->egqcm_intitule_complement,
        'remarquable'=>$li->egqcm_remarquable, 'interet_historique'=>$li->egqcm_interet_historique, 'interet_scientifique'=>$li->egqcm_interet_scientifique,
        'interet_pedagogique'=>$li->egqcm_interet_pedagogique, 'interet_esthetique'=>$li->egqcm_interet_esthetique, 'remarquable_info'=>$li->egqcm_remarquable_info];
      }
      if (!isset($data['egs'][$li->eg_id]['affleurements'][$li->affleurement_id]) && !is_null($li->affleurement_id)) {
        $data['egs'][$li->eg_id]['affleurements'][$li->affleurement_id] = ['nom' => $li->affleurement_nom, 'description'=>$li->affleurement_description];
        if ($with_geom) $data['egs'][$li->eg_id]['affleurements'][$li->affleurement_id]['geom'] = $li->affleurement_geom;
      }
      if (!isset($data['egs'][$li->eg_id]['photos'][$li->photo_eg_id]) && !is_null($li->photo_eg_id)) {
        $data['egs'][$li->eg_id]['photos'][$li->photo_eg_id] = ['url' => $li->photo_eg_url, 'legende'=>$li->photo_eg_description, 'type'=>$li->photo_eg_mimetype];
        if ($with_geom) $data['egs'][$li->eg_id]['affleurements'][$li->affleurement_id]['geom'] = $li->affleurement_geom;
      }
    }

    return $data;
  }

  // retourne tous les éléments géométriques du site
  public function getSubelements_geom($id) {
    $this->db->select('site.id as site_id, site.nom as site_nom, st_asGeoJson(site.geom) AS site_geom,
      eg.intitule AS eg_nom, eg.id AS eg_id, st_asGeoJson(eg.geom) AS eg_geom,
      affleurement.id AS affleurement_id, affleurement.nom AS affleurement_nom, st_asGeoJson(affleurement.geom) AS affleurement_geom')
      ->join('entite_geol AS eg', 'eg.site_id=site.id', 'left')
      ->join('affleurement', 'affleurement.eg_id=eg.id', 'left');
    $query = $this->db->get_where('site', ['site.id'=>$id]);
    $data = array();
    foreach ($query->result() as $li) {
      if (!isset($data['site'])) {
        $data['site'] = ['geom'=>$li->site_geom, 'properties'=>['nom'=>$li->site_nom, 'id'=>$li->site_id]];
      }
      if (!isset($data['egs'][$li->eg_id])) {
        $data['egs'][$li->eg_id] = ['geom'=>$li->eg_geom, 'properties'=>['nom'=>$li->eg_nom, 'id'=>$li->eg_id]];
      }
      if (!isset($data['affleurements'][$li->affleurement_id])) {
        $data['affleurements'][$li->affleurement_id] = ['geom'=>$li->affleurement_geom,
          'properties'=>['nom'=>$li->affleurement_nom, 'id'=>$li->affleurement_id, 'eg_id'=>$li->eg_id]];
      }
    }
    return $data;
  }


  public function is_editable($id) {
    if (!$this->auth->logged_in()) return FALSE;
    $query = $this->db
      ->select('espace_protege.group_id')
      ->join('espace_protege', 'ep_id=espace_protege.id')
      ->get_where($this->tableName, ['site.id'=>$id]);
    $res = $query->row();
    return $this->auth->in_group(['admin', $res->group_id]);
  }

  public function change_status($id_site, $status) {
    if (!$this->is_editable($id_site))
      return FALSE;
    $this->db->where('id', $id_site)
      ->update('espace_protege', array('statut_validation' => $status));
    return TRUE;
  }


  // récupère les données sismiques de SISFrance à partir du WFS du BRGM
  public function getSeismes($id) {
    $this->load->library('Webservices');
    $bboxData = $this->getBBox($id);

    $params = [
      'service'=> 'WFS',
      'version'=> '1.1.0',
      'request'=> 'GETFEATURE',
      'srsName'=> 'EPSG:4326',
      'typeName' => 'ms:SIS_INTENSITE',
      'bbox' => $bboxData->xmin . ',' . $bboxData->ymin . ',' . $bboxData->xmax . ',' . $bboxData->ymax
    ];
    $xml = $this->webservices->getServiceXML('http://geoservices.brgm.fr/risques', $params);

    $values = array();
    foreach ($xml->xpath('//gml:featureMember') as $ft) {
      $val = '(' . $ft->xpath('.//ms:max_int_calc')[0] . '::real,'
        . $ft->xpath('.//ms:nb_total')[0] . '::integer, st_flipCoordinates(st_geomFromGML(\''
        . $ft->xpath('.//ms:msGeometry')[0]->children('gml', TRUE)[0]->asXML() . '\'::text)))';
      $values[] = $val;
    }

    $req = 'SELECT max(nb_total) nb_total, max(max_int_calc) max_int_calc FROM (VALUES ' . implode(',', $values) .
      ' ) as communes (max_int_calc, nb_total, geom) JOIN site on st_intersects(communes.geom, site.geom)
      WHERE id=' . $this->db->escape($id) .' GROUP BY id';
    $res = $this->db->query($req)->row();
    return $res;
  }


  // indicateur d'avancement du remplissage
  public function getAdvancement($id) {
    $id = $this->db->escape($id);
    $req = "WITH eg_ids AS (select id from entite_geol eg where eg.site_id={$id})
    SELECT site_id, eg_id, rubrique, niveau, bool_or(done) done FROM (
      SELECT site_qcm.site_id, entite_geol_qcm.entite_geol_id AS eg_id, rubrique.id AS rubrique, rubrique.niveau, site_qcm.id is not null AS done
      FROM qcm
        LEFT JOIN site_qcm ON site_qcm.qcm_id=qcm.id AND site_qcm.site_id={$id}
        LEFT JOIN entite_geol_qcm ON entite_geol_qcm.qcm_id=qcm.id AND entite_geol_qcm.entite_geol_id IN (SELECT * FROM eg_ids)
        JOIN rubrique ON rubrique=rubrique.id
      WHERE rubrique.obligatoire
      UNION ALL
      SELECT complement_site.site_id, complement_eg.entite_geol_id, rubrique.id, rubrique.niveau, complement_eg.id is not null
      FROM qcm LEFT JOIN complement_site ON complement_site.question = qcm.question AND complement_site.site_id = {$id}
      LEFT JOIN complement_eg ON complement_eg.question = qcm.question AND complement_eg.entite_geol_id in (SELECT * FROM eg_ids)
      JOIN rubrique ON qcm.rubrique=rubrique.id
      WHERE rubrique.obligatoire
    ) sr1
    GROUP BY site_id, eg_id, rubrique, niveau";

    $res = $this->db->query($req)->result();
    return $res;
  }

  // indicateur de remplissage pour tous les sites
  public function getAdvancementAll() {
    $req = 'SELECT site_id, eg_id, rubrique, niveau, bool_or(done) done FROM (
      SELECT site_qcm.site_id, entite_geol_qcm.entite_geol_id AS eg_id, rubrique.id AS rubrique, rubrique.niveau, site_qcm.id is not null AS done
      FROM qcm
        LEFT JOIN site_qcm ON site_qcm.qcm_id=qcm.id
        LEFT JOIN entite_geol_qcm ON entite_geol_qcm.qcm_id=qcm.id
        JOIN rubrique ON rubrique=rubrique.id
      WHERE rubrique.obligatoire
      UNION ALL
      SELECT complement_site.site_id, complement_eg.entite_geol_id, rubrique.id, rubrique.niveau, complement_eg.id is not null
      FROM qcm LEFT JOIN complement_site ON complement_site.question = qcm.question
      LEFT JOIN complement_eg ON complement_eg.question = qcm.question
      JOIN rubrique ON qcm.rubrique=rubrique.id
      WHERE rubrique.obligatoire
    ) sr1
    GROUP BY site_id, eg_id, rubrique, niveau';

    $res = $this->db->query($req)->rersult();

    $rates = [];

    $total_sites = 0;
    foreach ($res as $li) {
      if ($li->site_id != NULL && !isset($rates[$li->site_id])) {
        $rates[$li->site_id] = ['nb_eg'=>0, 'nb_q'=>0, 'nb_q_eg'=>0, 'nb_done'=>0, 'nb_done_eg'=>0];
      }
      if ($li->niveau == 'site' && $li->site_id == NULL) $total_sites++;
      if ($li->niveau == 'site' && $li->done) $n_sites++;
      if ($li->niveau == 'EG' && $li->eg_id == NULL) $total_eg++;
      if ($li->niveau == 'EG' && $li->done) $n_eg++;
    }
    $taux_adv = $n_sites / $total_sites * 0.5 + ($n_eg / $total_eg) * (0.5 / count($data['entites_geol']));
    $data['avancement'] = round($taux_adv * 100);
  }


}
