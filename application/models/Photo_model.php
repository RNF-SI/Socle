<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Photo_model extends CI_Model {

  public function __construct() {
    $this->load->database();
  }

  public function getBySite($id_site) {
    $query = $this->db->get_where('photo', ['site_id' => $id_site]);
    return $query->result();
  }

  public function add_photo($data) {
    $this->db->insert('photo', $data);
  }

  public function getRandomPhoto() {
    $this->db
      ->select('photo.id, site.id as id_site, photo.url, photo.description, site.nom as nom_site')
      ->join('site', 'site_id=site.id');
    $query = $this->db
      ->order_by('random()')
      ->limit(1)
      ->get('photo');
    return $query->row();
  }

}
