<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qcm_model extends CI_Model {
  public function __construct() {
    $this->load->database();
  }

  public function getChoicesByRubrique($rubrique) {
    $query = $this->db->get_where("qcm", array('rubrique' => $rubrique));
    $data = array();
    foreach ($query->result() as $car) {
      if (!isset($data[$car->question]))
        $data[$car->question] = array();
      array_push($data[$car->question], $car);
    }
    return $data;
  }

  // recupère des questions correspondant aux ids
  public function getQuestionsForIds($ids) {
    $query = $this->db->where_in('id', $ids)->get('qcm');
    return $query->result();
  }

  public function getChildNodes($parent_id) {
    $query = $this->db->where('id_parent', $parent_id)
      ->order_by('id')
      ->get('ontology');
    return $query->result();
  }

}
