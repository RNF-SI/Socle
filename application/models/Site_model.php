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
    return TRUE;
    //$res = $this->get($id);
    //return $this->auth->in_group(['admin', $res->group_id]);
  }

  public function change_status($id_site, $status) {
    $this->db->where('id', $id_site)
      ->update('espace_protege', array('statut_validation' => $status));
  }


}
