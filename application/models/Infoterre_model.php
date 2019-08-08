<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Infoterre_model extends CI_Model {
  public function __construct() {
    $this->load->database();
  }

  public function get_eg_point($lat, $lng) {
    // Retourne l'entité géologique au point donné
    $this->db->select('ogc_fid, notation, descr, type_geol, ap_locale, geol_nat, age_deb.label label_age_deb,
      age_deb.id AS id_age_deb, age_deb.pix_min pix_min_deb, age_deb.pix_max pix_max_deb,
      age_fin.label label_age_fin,
      age_fin.id AS id_age_fin, age_fin.pix_min pix_min_fin, age_fin.pix_max pix_max_fin,
      lithologie, geochimie, st_asgeojson(wkb_geometry) AS geom');
    $this->db->join('infoterre.echelle AS age_deb', 'age_deb_id=age_deb.id', 'left');
    $this->db->join('infoterre.echelle AS age_fin', 'age_fin_id=age_fin.id', 'left');
    $this->db->where("st_intersects(wkb_geometry, st_setsrid(st_point($lng, $lat), 4326))");
    $req = $this->db->get('infoterre.s_fgeol');
    return $req->result();
  }
}
