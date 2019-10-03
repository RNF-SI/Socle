<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entite_geol_model extends Entite_abstract_model {
  protected $tableName = 'entite_geol';
  protected $qcmLinkTable = 'entite_geol_qcm';
  protected $store_user_info = TRUE;

  protected $commentTableName = 'commentaire_eg';
  protected $complementTableName = 'complement_eg';
  protected $geometry_format = 'MULTIPOINT';


  public function get($id_eg) {
    if (is_null($id_eg)) return;
    return $this->db
      ->join('echelle_geol', 'echelle_geol.id = ere_geol_id', 'left')
      ->join('infoterre.s_fgeol', 's_fgeol.ogc_fid=s_fgeol_id', 'left')
      ->join('infoterre.echelle as echelle_deb', 's_fgeol.age_deb_id=echelle_deb.id', 'left')
      ->join('infoterre.echelle as echelle_fin', 's_fgeol.age_fin_id=echelle_fin.id', 'left')
      ->select([
        $this->tableName.'.*',
        'echelle_geol.label AS ere_geol_label',
        'echelle_deb.label AS age_debut',
        'echelle_fin.label AS age_fin',
        's_fgeol.ogc_fid AS id_fgeol',
        'st_asGeoJson(s_fgeol.wkb_geometry) AS geom_bdcharm',
        'st_asGeoJson(geom) AS geojson'
      ])
      ->get_where($this->tableName, array($this->tableName.'.id'=>$id_eg))
      ->row();
  }

  public function getPath($id) {
    $this->db->select(['eg.id AS eg_id', 'eg.intitule',
      'site.id AS site_id', 'site.nom as site_nom',
     'espace_protege.id AS ep_id', 'espace_protege.nom as ep_nom', 'monosite'])
      ->from('entite_geol AS eg')
      ->join('site', 'site_id=site.id')
      ->join('espace_protege', 'ep_id=espace_protege.id')
      ->where(['eg.id'=>$id]);
    $res = $this->db->get()->row();
    $data = array();
    if (!$res->monosite) {
      $data[] = ['path'=>'espace/fiche_espace/'.$res->ep_id, 'title'=>$res->ep_nom];
    }
    $data[] = ['path'=>'site/fiche_site/'.$res->site_id, 'title'=>$res->site_nom];
    $data[] = ['path'=>'site/fiche_entite_geol/'.$id, 'title'=>$res->intitule];
    return $data;
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

  public function is_editable($id) {
    $this->load->model('site_model');
    $id_site = $this->get($id)->site_id;
    return $this->site_model->is_editable($id_site);
  }

  public function delete($id) {
    // suppression affleurements + photos
    $this->db->where('eg_id', $id)
      ->delete('affleurement');
    $this->db->where('eg_id', $id)
      ->delete('photo');
    parent::delete($id);
  }

}
