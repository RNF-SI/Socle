<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Modèle servant de mère aux autres modèles pouvant utiliser des QCMi.e. EP et EG
*/

class Entite_abstract_model extends CI_Model {
  protected $tableName;
  protected $qcmLinkTable;

  protected $commentTableName = 'commentaire';
  protected $complementTableName = 'complement';

  protected $entity;


  public function __construct() {
    $this->load->database();
  }

  protected function linkColumnName() {
    return $this->tableName . '_id';
  }


  private function _processGeometry($g) {
    // reconnait les formats textuels de géométrie et retourne la chaine à rentrer
    if (is_null($g) || $g === '') {
      $this->db->set('geom', NULL);
      return;
    }
    if (preg_match('/^[A-Z]+\(/', $g) == 1) { // WKT
      $func = 'st_geomFromText';
      $s = $g;
    } elseif (preg_match('/^\{"type":"(\w+)"/', $g, $match) == 1) { // geojson
      if ($match[1] == 'Feature') {
        $s = json_encode(json_decode($g)->geometry);
      } else {
        $s = $g;
      }
      $func = 'st_geomFromGeojson';
    }
    $procgeom = 'st_multi(st_setsrid(' . $func . '(' . $this->db->escape($s) . '), 4326))';
    $this->db->set('geom', $procgeom, FALSE);
  }


  public function get($id) {
    if (empty($this->entity) || $id != $this->entity->id) {
      $query = $this->db->get_where($this->tableName, array('id' => $id));
      $this->entity = $query->row();
    }
    return $this->entity;
  }

  public function getAll() {
    $query = $this->db->get($this->tableName);
    return $query->result();
  }

  public function update($id, $data) {
    if (isset($data['geom'])) {
      $this->_processGeometry($data['geom']);
      unset($data['geom']);
    }
    $this->db->set($data)
      ->where('id', $id)->update($this->tableName);
  }

  // ajout d'une entité
  public function add($data) {
    if (isset($data['geom'])) {
      $this->_processGeometry($data['geom']);
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
      ->where($this->linkColumnName(), $id)
      ->order_by('question, ordre_par_question');
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


  // recupère toutes les caractéristiques pour une rubriques
  // et précise si elles sont sélectionnées
  public function getCaracteristiquesForm($id, $rubrique = NULL) {
    $sousreq = $this->db
      ->select('qcm_id, info_complement, remarquable')
      ->where($this->linkColumnName(), $id)
      ->get_compiled_select($this->qcmLinkTable);
    $this->db->from('qcm')
      ->join('(' . $sousreq . ') AS sreq', 'qcm_id = qcm.id', 'left')
      ->order_by('question, ordre_par_question');
    if (! is_null($rubrique)) {
      $this->db->where('rubrique', $rubrique);
    }
    $query = $this->db->get();

    $res = $query->result();

    $data = array();
    foreach ($res as $car) {
      $car->checked = (! empty($car->qcm_id));
      if (!isset($data[$car->question]))
        $data[$car->question] = array();
      $data[$car->question][$car->id] = $car;
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
    $colname = $this->linkColumnName();

    $this->db->trans_start();
    if (isset($data['caracteristiques'])) {
      $cars = array();
      $complement_item = array();
      if (isset($data['info_complement'])) {
        foreach ($data['info_complement_id'] as $n => $iid) {
          if (! empty($data['info_complement'][$n]))
            $complement_item[$iid] = array('info_complement' => $data['info_complement'][$n]);
        }
        unset($data['info_complement']);
        unset($data['info_complement_id']);
      }

      if (isset($data['info_patrimonial'])) {
        foreach ($data['info_patrimonial'] as $iid) {
          if (! isset($complement_item[$iid])) {
            $complement_item[$iid] = array();
          }
          $complement_item[$iid]['patrimonial'] = TRUE;
        }
        unset($data['info_patrimonial']);
      }


      foreach ($data['caracteristiques'] as $item) {
        $li =  [
          $colname => $id,
          'qcm_id' => $item,
          'info_complement' => (empty($complement_item[$item]['info_complement']) ? NULL : $complement_item[$item]['info_complement']),
          'patrimonial' => (! empty($complement_item[$item]['patrimonial']))
         ];

        array_push($cars, $li);
      }
      unset($data['caracteristiques']);
    }

    // complements
    if (isset($data['complements_question'])) {
      $toinsert = array();
      $questionIds = array();
      foreach ($data['complements_question'] as $key => $id_question) {
        $val = $data['complements'][$key];
        if (! empty($val)) {
          array_push($toinsert, array('question' => $id_question, $colname => $id, 'elements' => $val));
          array_push($questionIds, $id_question);
        }
      }
      if (count($questionIds) > 0) {
        $this->db->where($colname, $id)
          ->where_in('question', $questionIds)
          ->delete($this->complementTableName);
        $this->db->insert_batch($this->complementTableName, $toinsert);
      }
      unset($data['complements_question']);
      unset($data['complements']);
    }

    // commentaires
    if (!empty(element('commentaire', $data))) {
      $this->db->where([$colname => $id, 'rubrique' => $rubrique])
        ->delete($this->commentTableName);
      $toinsert = [
        $colname => $id,
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
        ->where($colname, $id)
        ->delete($this->qcmLinkTable);

      if (isset($cars)) {
        $this->db->insert_batch($this->qcmLinkTable, $cars);
      }
    }
    $this->db->trans_complete();
  }


  public function getGeometry($id) {
    $query = $this->db->select('st_asGeoJson(geom) as geojson')
      ->get_where($this->tableName, ['id'=>$id]);
    return $query->row()->geojson;
  }

}
