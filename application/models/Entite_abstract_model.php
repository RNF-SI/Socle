<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Modèle servant de mère aux autres modèles pouvant utiliser des QCMi.e. EP et EG
*/

class Entite_abstract_model extends CI_Model {
  protected $tableName;
  protected $qcmLinkTable;

  protected $commentTableName = 'commentaire';
  protected $complementTableName = 'complement';


  public function __construct() {
    $this->load->database();
  }

  protected function linkColumnName() {
    return $this->tableName . '_id';
  }

  public function get($id) {
    $query = $this->db->get_where($this->tableName, array('id' => $id));
    return $query->row();
  }

  public function getAll() {
    $query = $this->db->get($this->tableName);
    return $query->result();
  }

  public function update($id, $data) {
    if (isset($data['geom'])) {
      $this->db->set('geom', $data['geom'], FALSE);
      unset($data['geom']);
    }
    $this->db->set($data)
      ->where('id', $id)->update($this->tableName);
  }

  // ajout d'une entité
  public function add($data) {
    if (isset($data['geom'])) {
      $this->db->set('geom', $data['geom'], FALSE);
      unset($data['geom']);
    }
    $this->db->set($data);
    $this->db->insert($this->tableName);
    return $this->db->insert_id();
  }



  public function getCaracteristiques($id, $rubrique = NULL) {
    // caractéristiques, groupé par question
    $this->db->from('qcm')
      ->join($this->qcmLinkTable, 'qcm_id = qcm.id')
      ->where($this->linkColumnName(), $id);
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

  public function getComplements($id, $questionIds) {
    if (count($questionIds) == 0) return array();

    $query = $this->db->where($this->linkColumnName(), $id)
      ->where_in('question', $questionIds)
      ->get($this->complementTableName);
    $res = array();
    foreach ($query->result() as $comp) {
      $res[$comp->question] = $comp;
    }
    return $res;
  }

  public function getComplementsRubrique($id, $rubrique) {

    $query = $this->db
      ->select($this->complementTableName . '.*')
      ->distinct()
      ->join('qcm', $this->complementTableName . '.question = qcm.question')
      ->where($this->linkColumnName(), $id)
      ->where('rubrique', $rubrique)
      ->get($this->complementTableName);
    $res = array();
    foreach ($query->result() as $comp) {
      $res[$comp->question] = $comp;
    }
    return $res;
  }

  public function getCommentaire($id, $rubrique) {
    $query = $this->db->get_where($this->commentTableName, [$this->linkColumnName() => $id, 'rubrique' => $rubrique]);
    return $query->row();
  }

  public function update_rubrique($id, $data, $rubrique) {
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
          array_push($toinsert, array('question' => $id_question, $this->linkColumnName() => $id, 'elements' => $val));
          array_push($questionIds, $id_question);
        }
      }
      if (count($questionIds) > 0) {
        $this->db->where($this->linkColumnName(), $id)
          ->where_in('question', $questionIds)
          ->delete($this->complementTableName);
        $this->db->insert_batch($this->complementTableName, $toinsert);
      }
      unset($data['complements_question']);
      unset($data['complements']);
    }

    // commentaires
    if (!empty(element('commentaire', $data))) {
      $this->db->where([$this->linkColumnName() => $id, 'rubrique' => $rubrique])
        ->delete($this->commentTableName);
      $toinsert = [
        $this->linkColumnName() => $id,
        'commentaire' => $data['commentaire'],
        'rubrique' => $rubrique
      ];
      $this->db->insert($this->commentTableName, $toinsert);
    }
    unset($data['commentaire']);

    if(!empty($data))
      $this->db->where('id', $id)->update($this->tableName, $data);

    // traitement des QCM
    if (isset($rubrique)) {
      $subquery = $this->db->select('id')
        ->where('rubrique', $rubrique)
        ->get_compiled_select('qcm');
      $this->db->where("qcm_id IN ($subquery)", NULL, FALSE)
        ->where($this->linkColumnName(), $id)
        ->delete($this->qcmLinkTable);

      if (isset($cars)) {
        $this->db->insert_batch($this->qcmLinkTable,
          array_map(function($elt) use ($id) {
            return array($this->linkColumnName() => $id, 'qcm_id' => $elt);
          }, $cars));
      }
    }
    $this->db->trans_complete();
  }

}
