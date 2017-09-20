<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Modèle servant de mère aux autres modèles pouvant utiliser des QCMi.e. EP et EG
*/

class Entite_abstract_model extends CI_Model {
  protected $tableName;
  protected $qcmLinkTable;
  protected $qcmLinkColumn;

  public function __construct() {
    $this->load->database();
  }

  public function get($id) {
    $query = $this->db->get_where($this->tableName, array('id' => $id));
    return $query->row();
  }

  public function getAll() {
    $query = $this->db->get($this->tableName);
    return $query->result();
  }

  public function getCaracteristiques($id, $rubrique = NULL) {
    // caractéristiques, groupé par question
    $this->db->from('qcm')
      ->join($this->qcmLinkTable, 'qcm_id = qcm.id')
      ->where($this->qcmLinkColumn, $id);
    if (! is_null($rubrique)) {
      $this->db->where('rubrique', $rubrique);
    }
    $query = $this->db->get();

    $res = $query->result();

    $data = array();
    foreach ($res as $car) {
      if (!isset($data[$car->question]))
        $data[$car->question] = array();
      array_push($data[$car->question], $car);
    }

    return $data;
  }

  

}
