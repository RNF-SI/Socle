<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Représente un espace sur lequel on peut gérer les droits
*/

class Espace_model extends Entite_abstract_model {
  protected $tableName = 'espace_protege';
  protected $geometry_format = 'MULTIPOLYGON';

  public function __construct() {
    $this->load->database();
  }


  public function get($id) {
    $this->entity = parent::get($id);
    // associe l'id du site dans le cas d'un monosite
    if ($this->entity->monosite && !isset($this->entity->site_id)) {
      $this->load->model('site_model');
      $site = $this->site_model->getByEspace($id)[0];
      $this->entity->site_id = $site->id;
    }
    return $this->entity;
  }

  public function getByGroup($id_group) {
    $query = $this->db->select('id')
      ->get_where($this->tableName, ['group_id' => $id_group]);
    $res = $query->row();
    return $res;
  }

  public function getByUser($id_user) {
    $query = $this->db->select('espace_protege.id, espace_protege.nom')
      ->from('espace_protege')
      ->join('users_groups', 'users_groups.group_id=espace_protege.group_id')
      ->join('users', 'users.id=users_groups.user_id')
      ->where('users.id',$id_user)
      ->get();
    $espaces = array();
    $res = $query->result();
    // $us = ['id' => $res[0]['user_id'], 'username' => $res[0]['user_username'], 'espaces' => array()];
    // foreach ($res as $li) {
    //     if (!is_null($li['id'])) {
    //       $espace = array('id' => $li['id'], 'nom'=> $li['nom']);
    //       $espaces[$espace['id']] = $espace;
    //     }
    //   }
    // $us['espaces'] = $espaces;
    return $res;
  }

  public function get_monosite_id($id) {
    $query = $this->db->select('site.id, monosite')
      ->join('site', 'site.ep_id=espace_protege.id')
      ->get_where('espace_protege', ['espace_protege.id'=>$id]);
    $res = $query->row();
    if (!$res->monosite)
      return FALSE;
    return $res->id;
  }

  // maj du monosite associé
  public function update_monosite($id, $data) {
    $sid = $this->get_monosite_id($id);
    if (!$sid) return FALSE;
    $this->load->model('site_model');
    $sdata = [
      'nom'=> $data['nom'],
      'geom'=>$data['geom'],
      'ep_id'=>$id
    ];
    $this->site_model->update($sid, $sdata);
  }


  // renvoie toutes les entités reliées à l'EP
  public function getAllChildren($id) {
    $query = $this->db->select('espace_protege.id as espace_id, espace_protege.nom as espace_nom, st_asgeojson(espace_protege.geom) as espace_geom, espace_protege.monosite as espace_monosite,
      site.id as site_id, site.nom as site_nom, st_geojson(site.geom) as site_geom,
      entite_geol.id as eg_id, entite_geol.nom as eg_nom, st_geojson(entite_geol.geom) as eg_geom,
      affleurement.id as affl_id, affleurement.nom as affl_nom, st_asGeoJson(affleurement.geom) as affl_geom')
      ->from($this->tableName)
      ->join('site', 'espace_id=espace_protege.id', 'left')
      ->join('entite_geol', 'site_id=site.id', 'left')
      ->join('affleurement', 'eg_id=entite_geol.id', 'left')
      ->where('espace_protege.id', $id)
      ->get();
    $sites = array();
    $res = $query->result_array();
    if (count($res) == 0) return;
    $ep = ['id' => $res[0]['espace_id'], 'nom' => $res[0]['espace_nom'], 'geom' => $res[0]['espace_geom'], 'monosite' => ($res[0]['espace_monosite']=='t'), 'sites' => array()];
    foreach ($res as $li) {
      if (!is_null($li['site_id']) && !isset($sites[$li['site_id']])) {
        $site = array('id' => $li['site_id'], 'nom'=> $li['nom_site'], 'geom'=>$li['site_geom'], 'entites_geol' => array());
        $sites[$site['id']] = $site;
      }
      if (!is_null($li['eg_id']) && !isset($sites[$li['site_id']]['entites_geol'][$li['eg_id']])) {
        $eg = ['id' => $li['eg_id'], 'nom'=>$li['eg_nom'], 'geom'=>$li['eg_geom'], 'affleurements'=>array()];
        $sites[$li['site_id']]['entites_geol'][$li['eg_id']] = $eg;
      }
      if (!is_null($li['affl_id']) && !isset($sites[$li['site_id']]['entites_geol'][$li['eg_id']]['affleurements'][$li['affl_id']])) {
        $affl = ['id' => $li['affl_id'], 'nom'=>$li['affl_nom'], 'geom'=>$li['affl_geom']];
        $sites[$li['site_id']]['entites_geol'][$li['eg_id']]['affleurements'][$li['affl_id']] = $affl;
      }
    }
    $ep['sites'] = $sites;
    return $ep;
  }

  public function getEspacesWithPhoto($limit=NULL) {
    $sreq = $this->db->select('ep_id, min(photo.id) pid')
      ->from('photo')
      ->join('site', 'photo.site_id=site.id')
      ->where('mimetype != \'application/pdf\'') // exclut les PDF
      ->group_by('ep_id')
      ->get_compiled_select();
    $this->db->select('photo.*, espace_protege.nom AS nom_espace, espace_protege.id AS espace_id')
      ->join("($sreq) sreq", 'sreq.pid=photo.id', NULL, FALSE)
      ->join('espace_protege', 'ep_id=espace_protege.id');
    if (!is_null($limit)) {
      $this->db->limit($limit);
    }
    return $this->db->get('photo')->result();
  }


}
