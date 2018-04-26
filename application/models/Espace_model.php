<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Représente un espace sur lequel on peut gérer les droits
*/

class Espace_model extends Entite_abstract_model {
  protected $tableName = 'espace_protege';

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


}
