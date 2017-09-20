<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Espace_protege_model extends CI_Model {
  public function __construct() {
    $this->load->database();
  }

  public function getEspace_protege($id_espace) {
    $query = $this->db->get_where("espace_protege", array('id' => $id_espace));
    return $query->row();
  }

  public function getAllEspaces() {
    $query = $this->db->get('espace_protege');
    return $query->result();
  }

  public function getCaracteristiques($id_ep, $rubrique = NULL) {
    // caractéristiques, groupé par question
    $this->db->from('qcm')
      ->join('espace_protege_qcm', 'qcm_id = qcm.id')
      ->where('espace_protege_id', $id_ep);
    if (! is_null($rubrique)) {
      $this->db->where('rubrique', $rubrique);
    }
    $query = $this->db->get();

    $res = $query->result();

    $data = array();
    foreach ($res as $car) {
      if (!isset($data[$car->question]))
        $data[$car->question] = array();
      array_push($data[$car->question], $car);
    }

    return $data;
  }

  // feuilles des cartes géol associées à l'EP (requete spatiale)
  public function getFeuillesCartes($id_ep) {
    $query = $this->db
      ->select(['emprise_cartes_geol.numero', 'emprise_cartes_geol.nom'])
      ->join('espace_protege_ref', 'espace_protege_ref.id_mnhn=espace_protege.code_national_ep')
      ->join('emprise_cartes_geol', 'st_intersects(emprise_cartes_geol.geom, espace_protege_ref.geom)')
      ->where('espace_protege.id', $id_ep)
      ->get('espace_protege');
    return $query->result();
  }

  public function getEntitesGeol($id_ep) {
    return $this->db->get_where('entite_geol', array('espace_protege_id' => $id_ep))->result();
  }

  public function getComplements($id_ep, $questionIds) {
    if (count($questionIds) == 0) return array();

    $query = $this->db->where('espace_protege_id', $id_ep)
      ->where_in('question', $questionIds)
      ->get('complement');
    $res = array();
    foreach ($query->result() as $comp) {
      $res[$comp->question] = $comp;
    }
    return $res;
  }

  public function getCommentaire($id_ep, $rubrique) {
    $query = $this->db->get_where('commentaire', ['espace_protege_id' => $id_ep, 'rubrique' => $rubrique]);
    return $query->row();
  }

  public function update_rubrique($id_ep, $data, $rubrique) {
    $this->db->trans_start();
    if (isset($data['caracteristiques'])) {
      $cars = $data['caracteristiques'];
      unset($data['caracteristiques']);
    }

    // complements
    if (isset($data['complements_question'])) {
      $toinsert = array();
      $questionIds = array();
      foreach ($data['complements_question'] as $key => $id_question) {
        $val = $data['complements'][$key];
        if (! empty($val)) {
          array_push($toinsert, array('question' => $id_question, 'espace_protege_id' => $id_ep, 'elements' => $val));
          array_push($questionIds, $id_question);
        }
      }
      if (count($questionIds) > 0) {
        $this->db->where('espace_protege_id', $id_ep)
          ->where_in('question', $questionIds)
          ->delete('complement');
        $this->db->insert_batch('complement', $toinsert);
      }
      unset($data['complements_question']);
      unset($data['complements']);
    }

    // commentaires
    if (!empty(element('commentaire', $data))) {
      $this->db->where(['espace_protege_id' => $id_ep, 'rubrique' => $rubrique])
        ->delete('commentaire');
      $toinsert = [
        'espace_protege_id' => $id_ep,
        'commentaire' => $data['commentaire'],
        'rubrique' => $rubrique
      ];
      $this->db->insert('commentaire', $toinsert);
    }
    unset($data['commentaire']);
    if(!empty($data))
      $this->db->where('id', $id_ep)->update('espace_protege', $data);

    // traitement des QCM
    if (isset($rubrique)) {
      $subquery = $this->db->select('id')
        ->where('rubrique', $rubrique)
        ->get_compiled_select('qcm');
      $this->db->where("qcm_id IN ($subquery)", NULL, FALSE)
        ->where('espace_protege_id', $id_ep)
        ->delete("espace_protege_qcm");

      if (isset($cars)) {
        $this->db->insert_batch('espace_protege_qcm',
          array_map(function($elt) use ($id_ep) {
            return array('espace_protege_id' => $id_ep, 'qcm_id' => $elt);
          }, $cars));
      }
    }
    $this->db->trans_complete();
  }

  // ajout d'un ep
  public function add($data) {
    $this->db->insert('espace_protege', $data);
    return $this->db->insert_id();
  }
}
