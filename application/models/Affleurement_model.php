<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affleurement_model extends Entite_abstract_model {
  protected $tableName = 'affleurement';

  public function getByEG($id_eg) {
    return $this->db->get_where($this->tableName, ['eg_id' => $id_eg])->result();
  }

}
