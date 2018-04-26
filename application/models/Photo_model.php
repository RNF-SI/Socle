<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Représente un espace sur lequel on peut gérer les droits
*/

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

}
