<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Modèle servant de mère aux autres modèles pouvant utiliser des QCM i.e. EP et EG
*/

class Entite_abstract_model extends CI_Model {
  protected $tableName;
  protected $qcmLinkTable;

  protected $commentTableName = 'commentaire';
  protected $complementTableName = 'complement';

  protected $entity;

  protected $has_geometry = TRUE;
  protected $geometry_format = 'POINT';
  protected $store_user_info = FALSE;


  public function __construct() {
    $this->load->database();
  }

  protected function linkColumnName() {
    return $this->tableName . '_id';
  }


  protected function log_debug($data) {
    log_message('DEBUG', print_r($data, TRUE));
  }


  private function _processGeometry($g) {
    // reconnait les formats textuels de géométrie et retourne la chaine à rentrer
    if (is_null($g) || $g === '') {
      $this->db->set('geom', NULL);
      return;
    }
    $g = html_entity_decode($g);
    if (preg_match('/^[A-Z]+\\(/', $g) == 1) { // WKT
      $func = 'st_geomFromText';
      $s = $g;
    } else { // geojson
      $json = json_decode($g);
      if ($json->type == 'Feature') {
        $s = json_encode($json->geometry);
      } else {
        $s = $g;
      }
      $func = 'st_geomFromGeojson';
    }
    $procgeom = 'st_setsrid(' . $func . '(' . $this->db->escape($s) . '), 4326)';
    if (substr($this->geometry_format, 0, 4) == 'MULTI') {
      $procgeom = 'st_multi(' . $procgeom . ')';
    }

    $this->db->set('geom', $procgeom, FALSE);
  }


  public function get($id) {
    if (empty($this->entity) || $id != $this->entity->id) {
      $this->db->select($this->tableName . '.*');
      if ($this->has_geometry) {
        $this->db->select('st_asGeoJson(geom) as geom, st_area(st_transform(geom, 2154)) AS surface');
      }
      $query = $this->db->get_where($this->tableName, array('id' => $id));
      $this->entity = $query->row();
    }
    return $this->entity;
  }

  // retourne toutes les entités d'une entité parente
  protected function getByParent($pid, $linkColName) {
    $this->db->select($this->tableName . '.*');
    if ($this->has_geometry) {
      $this->db->select('st_asGeoJson(geom) as geom');
    }
    $query = $this->db->get_where($this->tableName, array($linkColName => $pid));
    return $query->result();
  }

  public function getAll() {
    $this->db->select('*');
    if ($this->has_geometry) {
      $this->db->select('st_asGeoJson(geom) AS geom');
    }
    $query = $this->db->get($this->tableName);
    return $query->result();
  }

  public function update($id, $data) {
    if (isset($data['geom'])) {
      $this->_processGeometry($data['geom']);
      unset($data['geom']);
    }
    $this->update_user_date($id);
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


  public function getReponses($id) {
    // caractéristiques cochées
    $this->db->from($this->qcmLinkTable)
      ->where($this->linkColumnName(), $id);

    $query = $this->db->get();

    $res = $query->result();
    //$this->log_debug($res);

    $data = array();
    foreach ($res as $car) {
      $data[$car->qcm_id] = $car;
    }

    return $data;
  }


  // recupère toutes les caractéristiques pour une rubriques
  // et précise si elles sont sélectionnées
  public function getCaracteristiquesForm($id, $rubrique = NULL) {
    $subquery = $this->db
      ->select('qcm_id, info_complement, remarquable, interet_esthetique, interet_historique, interet_pedagogique, interet_scientifique, remarquable_info')
      ->where($this->linkColumnName(), $id)
      ->get_compiled_select($this->qcmLinkTable);
    $this->db->from('qcm')
      ->join('(' . $subquery . ') AS sreq', 'qcm_id = qcm.id', 'left')
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

  private function log($v) {
    log_message('debug', print_r($v, TRUE));
  }

  public function update_rubrique($id, $data, $rubrique) {
    $link_fields = [
      //'remarquable' => 'b',
      'interet_scientifique' => 'b',
      'interet_pedagogique' => 'b',
      'interet_esthetique' => 'b',
      'interet_historique' => 'b',
      'remarquable_info' => 't',
      'info_complement' => 't'
    ];
    $colname = $this->linkColumnName();

    $this->db->trans_start();

    if (isset($data['caracteristiques'])) {
      $cars = array();
      foreach ($data['caracteristiques'] as $car) {
        $cars[$car] = array('qcm_id' => $car, $colname => $id, 'remarquable' => FALSE);
      }
      unset($data['caracteristiques']);

      if (isset($data['info_complement'])) {
        foreach ($data['info_complement'] as $pos => $val) {
          if (! empty($val))
            $cars[$data['info_complement_id'][$pos]]['info_complement'] = $val;
        }
        unset($data['info_complement']);
        unset($data['info_complement_id']);
      }

      $corresp_id = $data['remarquable'];
      foreach ($data['remarquable'] as $numli => $qcm_id) {
          $cars[$qcm_id]['remarquable'] = !empty($qcm_id);
      }
      unset($data['remarquable']);

      foreach ($data as $field => $val) {
        if (is_array($val) && isset($link_fields[$field])) {
          foreach ($val as $pos => $iid) {
            if (! empty($iid)) {
              if ($link_fields[$field] == 'b') {
                $cars[$iid][$field] = TRUE;
              } else {
                $cars[$corresp_id[$pos]][$field] = $iid;
              }
            }
          }
          unset($data[$field]);
        }
      }

      unset($data['caracteristiques']);
    } else {
      // on s'assure que toutes les infos secondaires sont retirées
      unset($data['remarquable']);
      foreach ($link_fields as $field => $type) {
        unset($data[$field]);
      }
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

    // traitement des QCM

    // enregistrement des champs specifiques
    if(!empty($data))
      $this->db->where('id', $id)->update($this->tableName, $data);

    if (isset($rubrique)) {
      $subquery = $this->db->select('id')
        ->where('rubrique', $rubrique)
        ->get_compiled_select('qcm');
      $this->db->where("qcm_id IN ($subquery)", NULL, FALSE)
        ->where($colname, $id)
        ->delete($this->qcmLinkTable);

      if (isset($cars)) {
        // on s'assure que toutes les lignes ont le memes cles
        $keys = array();
        foreach ($cars as $li) {
          $keys = array_unique(array_merge($keys, array_keys($li)));
        }
        $template = array_combine($keys, array_fill(0, count($keys), NULL));
        $toinsert = array();
        foreach ($cars as $li) {
          if (!empty($li['qcm_id'])) {
            $toinsert[] = array_merge($template, $li);
          }
        }

        $this->db->insert_batch($this->qcmLinkTable, $toinsert);
      }
    }

    // actualisation date/user maj
    $this->update_user_date($id);
    $this->db->trans_complete();
  }


  public function update_user_date($id) {
    if ($this->store_user_info) {
      $uid = $this->auth->user()->row()->id;
      $this->db->where('id', $id)
        ->update($this->tableName, [
        'last_modified' => date(DateTime::ATOM),
        'modified_by_userid' => $uid
      ]);
    }
  }


  public function getGeometry($id, $additional_fields=NULL) {
    $this->db->select('st_asGeoJson(geom) as geojson');
    if (is_array($additional_fields)) {
      $this->db->select($additional_fields);
    }
    $res = $this->db->get_where($this->tableName, ['id'=>$id])->row_array();
    $data = ['properties' => []];
    foreach ($res as $key => $val) {
      if ($key == 'geojson') {
        $data['geom'] = $val;
      } else {
        $data['properties'][$key] = $val;
      }
    }
    return $data;
  }

  public function getBBox($id) {
    $this->db->select('st_xmin(geom) xmin, st_ymin(geom) ymin, st_xmax(geom) xmax, st_ymax(geom) ymax');
    $res = $this->db->get_where($this->tableName, ['id' => $id])->row();
    return $res;
  }


  public function saveChanges($id, $data) {
    // Enregistre les changements dans les données des QCM pour un unique item
    // (Pour tree view)
    $to_delete = [];
    $to_insert = [];
    $linkColumn = $this->linkColumnName();
    foreach ($data as $i => $item) {
      $to_delete[] = "($id, $i)";
      if ($item->checked) {
        $li = [
          $linkColumn => $id,
          'qcm_id' => $i,
          'remarquable' => isset($item->remarquable) ? $item->remarquable : FALSE
        ];
        // TODO: autres infos
        $to_insert[] = $li;
      }
    }

    $this->db->where("($linkColumn, qcm_id) IN (" . implode(',', $to_delete) . ')');
    $this->db->delete($this->qcmLinkTable);
    if (count($to_insert) > 0) {
      $this->db->insert_batch($this->qcmLinkTable, $to_insert);
    }
    return TRUE;
  }

  public function getRockAges($id) {
    // récupère les données BRGM intersectant l'entité
    if (! $this->has_geometry) return;

    $this->db->join('infoterre.s_fgeol',
      "st_intersects(s_fgeol.wkb_geometry, \"$this->tableName\".geom")
      ->join('infoterre.echelle AS echelle_deb', 'age_deb_id=echelle.id', 'left')
      ->join('infoterre.echelle AS echelle_fin', 'age_fin_id=echelle.id', 'left')
      ->select('ogc_fid, descr, age_deb_id, echelle_deb.label AS label_age_deb,
        age_fin_id, echelle_fin.label AS label_age_fin, st_asgeojson(s_fgeol.wkb_geometry) AS geom')
      ->get_where($this->tableName, ['id' => $id]);
  }

}
