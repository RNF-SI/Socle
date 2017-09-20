<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espace_ref_model extends CI_Model {
  public function __construct() {
    $this->load->database();
  }


  public function getEspaceRef($id_ref) {
    $query = $this->db
      ->select(['id', 'id_local', 'code_r_enp', 'nom_site', 'date_crea',
        'id_mnhn', 'surf_off'])
      ->get_where('espace_protege_ref', array('id_mnhn' => $id_ref));
    return $query->row();
  }


  // tous les espaces non encore enregistrés
  public function getEspacesRefAvailable () {
    $subquery = $this->db->select('code_national_ep')->get_compiled_select('espace_protege');
    $query = $this->db
      ->where('id_mnhn IS NOT NULL AND id_mnhn NOT IN ('.$subquery . ')', NULL, FALSE)
      ->order_by('nom_site')
      ->get('espace_protege_ref');
    return $query->result();
  }

  public function getEspaceRefGeom($id_ref) {
    $query = $this->db
      ->select(['id', 'id_local', 'code_r_enp', 'nom_site', 'date_crea',
        'id_mnhn', 'surf_off', 'st_asgeojson(geom) AS geom'])
      ->get_where('espace_protege_ref', array('id_mnhn' => $id_ref));
    $res = $query->row_array();
    $props = $res;
    unset($props['geom']);
    return array('properties' => $props, 'geom' => $res['geom']);
  }

}
