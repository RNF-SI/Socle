<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_model extends Entite_abstract_model {
  protected $tableName = 'site';
  protected $qcmLinkTable = 'site_qcm';
  protected $complementTableName = 'complement_site';


  public function getByEspace($id_ep) {
    $query = $this->db
      ->get_where($this->tableName, ['ep_id' => $id_ep]);
    return $query->result();
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
      ->join('espace_protege_ref', 'espace_protege_ref.id_mnhn=espace_protege.code_national_ep')
      ->join('emprise_cartes_geol', 'st_intersects(emprise_cartes_geol.geom, espace_protege_ref.geom)')
      ->where('espace_protege.id', $id_site)
      ->get($this->tableName);
    return $query->result();
  }

  public function getEntitesGeol($id_site) {
    return $this->db->get_where('entite_geol', array('site_id' => $id_site))->result();
  }

  public function is_editable($id) {
    $query = $this->db
      ->select('espace_protege.group_id')
      ->join('espace_protege', 'ep_id=espace_protege.id')
      ->get_where($this->tableName, ['site.id'=>$id]);
    $res = $query->row();
    return $this->auth->in_group(['admin', $res->group_id]);
  }

  public function change_status($id_site, $status) {
    $this->db->where('id', $id_site)
      ->update('espace_protege', array('statut_validation' => $status));
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


}
