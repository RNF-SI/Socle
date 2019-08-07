<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Infoterre_model extends CI_Model {
  public function __construct() {
    $this->load->database();
  }

  public function get_eg_point($lat, $lng) {
    // Retourne l'entité géologique au point donné
    $this->db->select('ogc_fid, notation, descr, type_geol, ap_locale, geol_nat, age_deb.label,
      age_deb.id AS id_age_deb, age_deb.pix_min, age_deb.pix_max,
      lithologie, geochimie, st_asgeojson(wkb_geometry) AS geom');
    $this->db->join('infoterre.echelle AS age_deb', 'age_deb_id=age_deb.id', 'left');
    $this->db->where("st_intersects(wkb_geometry, st_setsrid(st_point($lng, $lat), 4326))");
    $req = $this->db->get('infoterre.s_fgeol');
    return $req->result();
  }
}
