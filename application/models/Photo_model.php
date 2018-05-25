<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Photo_model extends Entite_abstract_model {
  protected $tableName = 'photo';
  protected $has_geometry = FALSE;
  protected $store_user_info = FALSE;
  

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

  public function delete($id) {
    // suppression des thumbnails et du fichier principal
    $photo_path = $this->config->item('photo_folder');
    $thumb_path = $this->config->item('thumbnail_folder');
    $photo = $this->get($id);
    unlink($photo_path . '/' . $photo->url);
    $pinfo = pathinfo($photo->url);
    $patern = $thumb_path . '/' . $pinfo['filename'] . '-*px.' . $pinfo['extension'];
    foreach (glob($patern) as $path) {
      unlink($path);
    }
    $this->db->delete($this->tableName, ['id'=>$id]);
    return TRUE;
  }

}
