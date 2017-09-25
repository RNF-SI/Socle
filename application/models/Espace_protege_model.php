<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espace_protege_model extends Entite_abstract_model {
  protected $tableName = 'espace_protege';
  protected $qcmLinkTable = 'espace_protege_qcm';


  // feuilles des cartes géol associées à l'EP (requete spatiale)
  public function getFeuillesCartes($id_ep) {
    $query = $this->db
      ->select(['emprise_cartes_geol.numero', 'emprise_cartes_geol.nom'])
      ->join('espace_protege_ref', 'espace_protege_ref.id_mnhn=espace_protege.code_national_ep')
      ->join('emprise_cartes_geol', 'st_intersects(emprise_cartes_geol.geom, espace_protege_ref.geom)')
      ->where('espace_protege.id', $id_ep)
      ->get($this->tableName);
    return $query->result();
  }

  public function getEntitesGeol($id_ep) {
    return $this->db->get_where('entite_geol', array('espace_protege_id' => $id_ep))->result();
  }

  public function is_editable($id) {
    $res = $this->get($id);
    return $this->auth->in_group(['admin', $res->group_id]);
  }


}
