<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entite_geol_model extends CI_Model {
  private $tableName = 'entite_geol';

  public function __construct() {
    $this->load->database();
  }

  public function getEntiteGeol($id_eg) {
    if (is_null($id_eg)) return;
    return $this->db
      ->join('echelle_geol', 'echelle_geol.id = id_ere_geol', 'left')
      ->select([$this->tableName.'.*', 'echelle_geol.label AS ere_geol_label'])
      ->get_where($this->tableName, array($this->tableName.'.id'=>$id_eg))->row();
  }

  public function add($data) {
    var_dump($data);
    unset($data['coords']);
    $this->db->insert($this->tableName, $data);
    return $this->db->insert_id();
  }

  public function update($id_eg, $data) {
    $this->db->where('id', $id_eg)->update($this->tableName);
  }

  // retourne l'échelle géol sous forme d'arbre
  public function echelle_geol() {
    $res = $this->db->get('echelle_geol')->result();

    function getChildren($elt, $liste) {
      $children = array();
      foreach ($liste as $e) {
        if($e->parent == $elt->id) {
          array_push($children, getChildren($e, $liste));
        }
      }
      $elt->children = $children;
      return $elt;
    }

    $top_level = array_filter($res, function($e) { return is_null($e->parent); });
    $struct = array();
    foreach ($top_level as $elt) {
      array_push($struct, getChildren($elt, $res));
    }

    return $struct;
  }

}
