<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entite_geol_model extends Entite_abstract_model {
  protected $tableName = 'entite_geol';
  protected $qcmLinkTable = 'entite_geol_qcm';

  protected $commentTableName = 'commentaire_eg';
  protected $complementTableName = 'complement_eg';


  public function get($id_eg) {
    if (is_null($id_eg)) return;
    return $this->db
      ->join('echelle_geol', 'echelle_geol.id = id_ere_geol', 'left')
      ->select([$this->tableName.'.*', 'echelle_geol.label AS ere_geol_label'])
      ->select('st_x(geom) || \',\' || st_y(geom) AS coords')
      ->get_where($this->tableName, array($this->tableName.'.id'=>$id_eg))
      ->row();
  }

  public function add($data) {
    $data['geom'] = 'st_geometryAsText(st_Point(' . $data['coords'] . '))';
    unset($data['coords']);
    return parent::insert($data);
  }

  public function update($id_eg, $data) {
    $data['geom'] = 'st_setSRID(st_Point(' . $data['coords'] . '), 4326)';
    unset($data['coords']);
    return parent::update($id_eg, $data);
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
